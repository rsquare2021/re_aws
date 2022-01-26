<?php

namespace App\Http\Controllers\User;

use App\Facades\UserRoute;
use App\Http\Controllers\Controller;
use App\Mail\Applied;
use App\Models\Apply;
use App\Models\ApplyStatus;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\DeliveryTime;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\UserCampaignPoint;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CatalogApplyController extends Controller
{
    public function setProductToApply(Request $request, $campaign_id, $gift_id)
    {
        $safe_data = $request->validate([
            "quantity" => ["required", "min:1"],
            "product_id" => ["required", "integer"],
        ]);

        $safe_data["step"] = "set_product";
        $safe_data["campaign_id"] = $campaign_id;
        $safe_data["gift_id"] = $gift_id;
        $request->session()->put("apply", $safe_data);

        return redirect(route("campaign.catalog.gift.apply.address", ["campaign_id" => $campaign_id, "gift_id" => $gift_id]));
    }

    public function editAddressToApply(Request $request, $campaign_id, $gift_id)
    {
        $apply_session = $request->session()->get("apply", false);
        if($apply_session === false or $apply_session["step"] !== "set_product") {
            // 一連の応募フロー以外でのアクセスはエラー。
            $request->session()->forget("apply");
            return $this->redirectTo($campaign_id, $gift_id);
        }

        if($campaign_id != $apply_session["campaign_id"] or $gift_id != $apply_session["gift_id"]) {
            return $this->redirectTo($campaign_id, $gift_id);
        }

        $user = UserRoute::user();
        $point = UserRoute::point();
        $campaign = UserRoute::campaign();
        $gift = CampaignProduct::whereCampaign($campaign_id)->canApply()->findOrFail($gift_id);
        $product = Product::findOrFail($apply_session["product_id"]);
        $deliveries = DeliveryTime::get();


        // 前回入力した発送先を初期データにする。
        $default_address = ShippingAddress::whereHas("apply", function($query) use($campaign_id) {
                $query->join("campaign_products", "applies.campaign_product_id", "=", "campaign_products.id")
                    ->where("applies.user_id", Auth::guard("user")->id())
                    ->where("campaign_products.campaign_id", $campaign_id);
            })
            ->orderBy("updated_at", "desc")
            ->firstOrNew([]);

        // 発送先履歴
        $address_histories = ShippingAddress::whereHas("apply", function($query) use($campaign_id) {
                $query->join("campaign_products", "applies.campaign_product_id", "=", "campaign_products.id")
                    ->where("applies.user_id", Auth::guard("user")->id())
                    ->where("campaign_products.campaign_id", $campaign_id);
            })
            ->select([
                "last_name",
                // "first_name",
                "last_name_kana",
                // "first_name_kana",
                "post_code",
                "prefectures",
                "municipalities",
                "address_code",
                "building",
                "tel",
                "delivery_time_id",
            ])
            ->distinct()
            ->get();

        return view("user.gift.point.order", [
            'category_name' => 'gift',
            'page_name' => 'gift_order',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '発送先の入力（ポイントカタログ交換画面）',
            "campaign_id" => $campaign_id,
            "gift" => $gift,
            "product" => $product,
            "quantity" => $apply_session["quantity"],
            "point" => $point,
            "default_address" => $default_address,
            "address_histories" => $address_histories,
            "campaign" => $campaign,
            "deliveries" => $deliveries,
            "user" => $user,
        ]);
    }

    public function setAddressToApply(Request $request, $campaign_id, $gift_id)
    {
        // リクエストデータとセッションデータの関係性が正しいかどうか検証する。
        $apply_session = $request->session()->get("apply", false);
        if($apply_session === false or $apply_session["step"] !== "set_product") {
            // エラー
            $request->session()->forget("apply");
            return $this->redirectTo($campaign_id, $gift_id);
        }

        if($campaign_id != $apply_session["campaign_id"] or $gift_id != $apply_session["gift_id"]) {
            return $this->redirectTo($campaign_id, $gift_id);
        }

        // リクエストデータをバリデーション
        $safe_data = $request->validate([
            "last_name" => ["required", "string"],
            // "first_name" => ["required", "string"],
            "last_name_kana" => ["required", "regex:/^[ァ-ヶー]+$/u"],
            // "first_name_kana" => ["required", "regex:/^[ァ-ヶー]+$/u"],
            "post_code" => ["required", "regex:/^\d{3}[-]\d{4}$/"],
            "prefectures" => ["required", "string"],
            "municipalities" => ["required", "string"],
            "address_code" => ["required", "string"],
            "building" => ["nullable", "string"],
            "tel" => ["required", "regex:/^\d[\d-]*\d$/"],
            "delivery_time_id" => ["required", "integer"],
        ]);

        // 応募データを作るうえで、キャンペーン、ギフト、商品の関係性が正しいかどうか検証する。
        $gift = CampaignProduct::findOrFail($gift_id);

        // $rule = $gift->getRule();
        // if(!$rule instanceof CatalogGiftRule) {}
        // if(!$rule->canApply($campaign_id) {}
        // if(!$rule->getProduct()->include($safe_data["product_id"])) {}
        if(!$gift->is_catalog_product()) throw new Exception("この景品には応募できません。");
        if(!$gift->can_apply($campaign_id)) throw new Exception("この景品には応募できません。");

        if($gift->product->id != $apply_session["product_id"]) {
            if($gift->product->variations->where("id", $apply_session["product_id"])->isEmpty()) {
                throw new Exception("この景品には応募できません。");
            }
        }

        // エンドユーザーの整合性チェック
        $point = UserCampaignPoint::getLoggedInUserPoint($campaign_id);
        if($point->remaining_point < $gift->point * $apply_session["quantity"]) {
            throw new Exception("ポイント不足のため応募できません。");
        }

        $user = $request->user();
        if(!$user->hasVerifiedEmail()) {
            throw new Exception("メールアドレスが未設定のため応募できません。");
        }

        // 応募ロジック
        $apply = null;
        DB::transaction(function() use(&$apply, $safe_data, $apply_session, $gift, $point) {
            // 応募データ生成
            $apply = Apply::create([
                "quantity" => $apply_session["quantity"],
                "apply_status_id" => ApplyStatus::APPLYING,
                "user_id" => $point->user_id,
                "campaign_product_id" => $gift->id,
                "product_id" => $apply_session["product_id"],
            ]);
            $address = ShippingAddress::create($safe_data);
            $apply->shipping_address()->associate($address);
            $apply->changeStatus(ApplyStatus::WAITING_ADDRESS);
            // 応募分のポイントをマイナス
            $point->remaining_point -= $gift->point * $apply_session["quantity"];
            $point->save();
        });

        Mail::send(new Applied($user, Campaign::findOrFail($campaign_id), $apply));

        $request->session()->forget("apply");

        return view("user.gift.point.order_complete", [
            'category_name' => 'gift',
            'page_name' => 'gift_order_complete',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '景品交換完了（ポイントカタログ交換）',
        ]);
    }

    protected function redirectTo($campaign_id, $gift_id)
    {
        return redirect(route('campaign.catalog.gift.show', [$campaign_id, $gift_id]));
    }
}
