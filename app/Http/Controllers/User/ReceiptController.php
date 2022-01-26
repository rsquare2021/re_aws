<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\UserCampaignPoint;
use App\Models\Receipt;
use App\Models\FlatShopTreeElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function index($campaign_id)
    {
        $user = Auth::guard("user")->user();
        $user_id = Auth::id();
        $campaign = Campaign::findOrFail($campaign_id);
        $point = UserCampaignPoint::firstOrNew(["user_id" => $user->id, "campaign_id" => $campaign_id]);
        $receipt = Receipt::where('campaign_id',$campaign_id)->where('user_id',$user_id)->orderBy('id', 'desc')->get();
        return view("user.receipt.list", [
            'category_name' => 'receipt',
            'page_name' => 'receipt_list',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'レシート送信履歴',
            "campaign" => $campaign,
            "point" => $point,
            "campaign_id" => $campaign_id,
            "user" => $user,
            "receipts" => $receipt,
        ]);
    }
    public function snap($campaign_id)
    {
        $user = Auth::guard("user")->user();
        $user_id = Auth::id();
        $campaign = Campaign::findOrFail($campaign_id);
        $campaign_shop_tree = "1";
        $point = UserCampaignPoint::firstOrNew(["user_id" => $user->id, "campaign_id" => $campaign_id]);
        $parents = FlatShopTreeElement::where('depth','=',2)->get();
        $children = FlatShopTreeElement::where('depth','=',3)->get();
        return view("user.snap", [
            'category_name' => 'receipt',
            'page_name' => 'receipt',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'レシート送信画面',
            "campaign" => $campaign,
            "point" => $point,
            "campaign_id" => $campaign_id,
            "user" => $user,
            "campaign_shop_tree" => $campaign_shop_tree,
            "parents" => $parents,
            "children" => $children,
        ]);
    }
}
