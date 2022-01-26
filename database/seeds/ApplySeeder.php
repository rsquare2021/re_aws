<?php

use App\Models\ApplyStatus;
use Illuminate\Database\Seeder;

class ApplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // マスタデータ

        DB::table("apply_statuses")->insert([
            "id" => ApplyStatus::APPLYING,
            "name" => "応募初期状態",
        ]);
        DB::table("apply_statuses")->insert([
            "id" => ApplyStatus::WAITING_LOTTERY,
            "name" => "抽選待ち",
        ]);
        DB::table("apply_statuses")->insert([
            "id" => ApplyStatus::LOST_LOTTERY,
            "name" => "落選",
        ]);
        DB::table("apply_statuses")->insert([
            "id" => ApplyStatus::WAITING_ADDRESS,
            "name" => "宛先入力待ち",
        ]);
        DB::table("apply_statuses")->insert([
            "id" => ApplyStatus::CANCEL,
            "name" => "キャンセル",
        ]);
        DB::table("apply_statuses")->insert([
            "id" => ApplyStatus::SENT_PRODUCT,
            "name" => "発送済み",
        ]);
        DB::table("apply_statuses")->insert([
            "id" => ApplyStatus::DEFECTING_ADDRESS,
            "name" => "宛先不備",
        ]);

        // 応募

        DB::table("applies")->insert([
            "quantity" => 2,
            "apply_status_id" => ApplyStatus::SENT_PRODUCT,
            "user_id" => 1,
            "campaign_product_id" => 1,
            "product_id" => 13,
            "shipping_address_id" => 1,
            "created_at" => "2022-11-2 09:52:49",
            "updated_at" => "2022-11-2 09:52:49",
        ]);
        DB::table("applies")->insert([
            "quantity" => 1,
            "apply_status_id" => ApplyStatus::WAITING_ADDRESS,
            "user_id" => 1,
            "campaign_product_id" => 1,
            "product_id" => 14,
            "created_at" => "2022-11-3 10:17:22",
            "updated_at" => "2022-11-3 10:17:22",
        ]);
        DB::table("applies")->insert([
            "quantity" => 2,
            "apply_status_id" => ApplyStatus::WAITING_ADDRESS,
            "user_id" => 1,
            "campaign_product_id" => 1,
            "product_id" => 13,
            "shipping_address_id" => 2,
            "created_at" => "2022-11-3 18:02:58",
            "updated_at" => "2022-11-3 18:02:58",
        ]);

        // 宛先

        DB::table("shipping_addresses")->insert([
            "last_name" => "加納",
            "first_name" => "雅士",
            "last_name_kana" => "カノウ",
            "first_name_kana" => "マサシ",
            "post_code" => "446-0076",
            "prefectures" => "愛知県",
            "municipalities" => "安城市美園町",
            "address_code" => "2-12-9",
            "building" => null,
            "tel" => "09042352965",
            "delivery_time_id" => 1,
        ]);
        DB::table("shipping_addresses")->insert([
            "last_name" => "山田",
            "first_name" => "太郎",
            "last_name_kana" => "ヤマダ",
            "first_name_kana" => "タロウ",
            "post_code" => "440-0881",
            "prefectures" => "愛知県",
            "municipalities" => "豊橋市広小路",
            "address_code" => "1丁目1-18",
            "building" => "ユメックスビル8F",
            "tel" => "0532-31-5334",
            "delivery_time_id" => 2,
        ]);
    }
}
