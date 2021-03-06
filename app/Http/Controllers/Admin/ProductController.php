<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use App\Models\ApplyStatus;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Supplier;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SplFileObject;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with("product_category", "supplier")->isNotVariation()->get();
        return view("admin.product.list", [
            'category_name' => 'product',
            'page_name' => 'product',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '景品一覧',
            "products" => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::getProductCategoryList();
        $suppliers = Supplier::get();
        return view("admin.product.create", [
            'category_name' => 'product',
            'page_name' => 'product_create',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '景品追加',
            "product_categories" => $categories,
            "suppliers" => $suppliers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            "variations" => $request->input("variations", []),
            "images" => $request->input("images", []),
        ]);

        $request->validate([
            "name" => ["required", "string"],
            "product_category_id" => ["nullable", "integer"],
            "catalog_basic_point" => ["nullable", "integer"],
            "basic_win_limit" => ["nullable", "integer"],
            "maker_name" => ["nullable", "string"],
            "maker_url" => ["nullable", "string"],
            "description_1" => ["nullable", "string"],
            "description_2" => ["nullable", "string"],
            "notice" => ["nullable", "string"],
            "supplier_id" => ["required", "integer"],
            "operation_id" => ["nullable", "string"],
            "variations" => ["present", "array"],
            "variations.*.variation_name" => ["required", "string"],
            "variations.*.operation_id" => ["nullable", "string"],
            "images" => ["present", "array"],
            "images.*.image_file" => ["filled", "file"],
        ]);

        DB::transaction(function() use($request) {
            $product = Product::create($request->all());
            // バリエーション生成
            $variations = collect($request->variations)->map(function($v) use($request) {
                $p = new Product();
                $p->fill($request->all());
                $p->variation_name = $v["variation_name"];
                $p->operation_id = $v["operation_id"];
                $p->save();
                return $p;
            });
            $product->variations()->saveMany($variations);
            // 画像ファイル生成
            $images = collect($request->images)->map(function($v) use($product) {
                $file = $v["image_file"];
                $file_name = $file->getClientOriginalName();
                $file->move(public_path("uploads/"), $file_name);
                $image = new ProductImage();
                $image->fill([
                    "path" => $file_name,
                ]);
                $image->product()->associate($product);
                $image->save();
                return $image;
            });
            $product->images()->saveMany($images);
        });

        return redirect()->route("admin.product.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with(["variations", "images"])->isNotVariation()->findOrFail($id);
        $categories = ProductCategory::getProductCategoryList();
        $suppliers = Supplier::get();
        return view("admin.product.edit", [
            'category_name' => 'product',
            'page_name' => 'product_edit',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '景品修正',
            "product" => $product,
            "product_categories" => $categories,
            "suppliers" => $suppliers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        // ddd($request->all());
        $request->merge([
            "variations" => $request->input("variations", []),
            "images" => $request->input("images", []),
        ]);

        $safe_data = $request->validate([
            "name" => ["required", "string"],
            "product_category_id" => ["nullable", "integer"],
            "catalog_basic_point" => ["nullable", "integer"],
            "basic_win_limit" => ["nullable", "integer"],
            "maker_name" => ["nullable", "string"],
            "maker_url" => ["nullable", "string"],
            "description_1" => ["nullable", "string"],
            "description_2" => ["nullable", "string"],
            "notice" => ["nullable", "string"],
            "supplier_id" => ["required", "integer"],
            "operation_id" => ["nullable", "string"],
            "variations" => ["present", "array"],
            "variations.*.id" => ["filled", "integer"],
            "variations.*.variation_name" => ["required", "string"],
            "variations.*.operation_id" => ["nullable", "string"],
            "images" => ["present", "array"],
            "images.*.id" => ["filled", "integer"],
            "images.*.path" => ["required_with:images.*.id", "string"],
            "images.*.image_file" => ["required_without:images.*.id", "file"],
        ]);

        // ddd($request->all());

        DB::transaction(function() use($safe_data, $product) {
            $product->update($safe_data);
            // バリエーション設定
            // 削除
            $old_ids = $product->variations->pluck("id");
            $new_ids = collect($safe_data["variations"])->pluck("id");
            $delete_ids = $old_ids->diff($new_ids);
            Product::whereIn("id", $delete_ids)->delete();
            // 追加
            $variations = collect($safe_data["variations"])->map(function($v) use($safe_data) {
                $p = Product::findOrNew($v["id"] ?? "");
                $p->fill($safe_data);
                $p->variation_name = $v["variation_name"];
                $p->operation_id = $v["operation_id"];
                $p->save();
                return $p;
            });
            $product->variations()->saveMany($variations);
            // 画像ファイル設定
            // 削除
            $old_ids = $product->images->pluck("id");
            $new_ids = collect($safe_data["images"])->pluck("id");
            $delete_ids = $old_ids->diff($new_ids);
            ProductImage::whereIn("id", $delete_ids)->delete();
            // 追加
            collect($safe_data["images"])->where("id", null)->each(function($v) use($product) {
                $this->createProductImage($product, $v);
            });
        });

        return redirect()->route("admin.product.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 外部キー制約でエラーになると思うのでソフトデリートに変えるべき。
        Product::destroy($id);
        return back();
    }

    public function uploadCsv()
    {
        return view("kano.admin.product.upload_csv");
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            "csv" => ["required", "file"],
        ]);

        $file_info = $request->file("csv");
        $file_object = $file_info->openFile();
        $file_object->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
        $loop_counter = 0;
        foreach($file_object as $row) {
            if($loop_counter++ < 2) continue;
            $t = [];
            $t["supplier_id"] = $row[1];
            $t["operation_id"] = $row[2];
            $t["name"] = $row[3];
            $t["variation_name"] = $row[4];
            $t["category_name"] = $row[5];
            $t["description"] = $row[7];
            $t["notice"] = $row[9];
            $t["maker_url"] = $row[10];
            $records[] = $t;
        }

        $request->merge([
            "content" => $records,
        ]);

        $request->validate([
            "content" => ["required", "array"],
            "content.*.supplier_id" => ["required", "integer"],
            "content.*.operation_id" => ["required", "string"],
            "content.*.name" => ["required", "string"],
            "content.*.variation_name" => ["nullable", "string"],
            "content.*.category_name" => ["nullable", "string"],
            "content.*.description" => ["present", "string"],
            "content.*.notice" => ["present", "string"],
            "content.*.maker_url" => ["nullable", "string"],
        ]);

        list($variation_items, $single_items) = collect($records)->partition(function($v) {
            return $v["variation_name"];
        });

        DB::transaction(function() use($variation_items, $single_items) {
            // バリエーション景品
            $variation_items = $variation_items->groupBy(function($v) {
                return mb_split("-", $v["operation_id"])[0];
            });
            foreach($variation_items as $k => $records) {
                $p = $records[0];
                $category = ProductCategory::firstOrNew(["name" => $p["category_name"]]);
                $parent = Product::updateOrCreate(
                    [
                        "supplier_id" => $p["supplier_id"],
                        "operation_id" => $k,
                    ],
                    [
                        "name" => $p["name"],
                        "product_category_id" => $category->id,
                        "description" => $p["description"],
                        "notice" => $p["notice"],
                        "maker_url" => $p["maker_url"],
                    ],
                );

                foreach($records as $record) {
                    Product::updateOrCreate(
                        [
                            "supplier_id" => $record["supplier_id"],
                            "operation_id" => $record["operation_id"],
                        ],
                        [
                            "name" => $record["name"],
                            "product_category_id" => $category->id,
                            "description" => $record["description"],
                            "notice" => $record["notice"],
                            "maker_url" => $record["maker_url"],
                            "variation_parent_id" => $parent->id,
                            "variation_name" => $record["variation_name"],
                        ],
                    );
                }
            }

            // 単品の景品
            foreach($single_items as $record) {
                $record = (object)$record;
                $category = ProductCategory::firstOrNew(["name" => $record->category_name]);
                Product::updateOrCreate(
                    [
                        "supplier_id" => $record->supplier_id,
                        "operation_id" => $record->operation_id,
                    ],
                    [
                        "name" => $record->name,
                        "product_category_id" => $category->id,
                        "description" => $record->description,
                        "notice" => $record->notice,
                        "maker_url" => $record->maker_url,
                    ],
                );
            }
        });
        return redirect(route("admin.product.index"));
    }

    public function exportShippingCsv($yyyymmdd)
    {
        // 指定日付のフォーマットチェック
        if(strlen($yyyymmdd) != 8) {
            return back();
        }

        $y = substr($yyyymmdd,0,4);
        $m = substr($yyyymmdd,4,2);
        $d = substr($yyyymmdd,6,2);
        if(!checkdate($m, $d, $y)) {
            return back();
        }

        $date = DateTimeImmutable::createFromFormat("Ymd", $y.$m.$d)->setTime(5, 0);

        // CSVファイルがまだ無ければ作成する。
        $filename = $y.$m.$d.".csv";
        if(!Storage::exists($filename)) {
            $stream = fopen("php://temp", "rw");
            // stream_filter_prepend($stream, "convert.iconv.utf-8/cp932//TRANSLIT");

            $targets = Apply::with("shipping_address", "product.product_category")->where("shipping_address_id", "<>", null)
                ->whereIn("apply_status_id", [ApplyStatus::WAITING_ADDRESS])
                ->whereBetween("updated_at", [$date->add(DateInterval::createFromDateString("-1day")), $date->add(DateInterval::createFromDateString("-1second"))])
                ;
            $targets->chunk(100, function($applies) use($stream) {
                foreach($applies as $apply) {
                    $fields = [
                        $apply->id, // システムID
                        $apply->created_at, // 交換申請日時
                        $apply->quantity, // サプライヤー番号
                        $apply->product->operation_id, // 管理番号
                        $apply->product->name, // 商品名
                        $apply->product->variation_name, // バリエーション名
                        "メーカーブランド名が入る。", // メーカー・ブランド名
                        $apply->product->product_category->name, // 分類名
                        $apply->quantity, // 個数
                        $apply->shipping_address->last_name, // 姓
                        $apply->shipping_address->first_name, // 名
                        $apply->shipping_address->post_code, // 郵便番号
                        $apply->shipping_address->prefectures, // 都道府県
                        $apply->shipping_address->municipalities, // 市区町村
                        $apply->shipping_address->address_code, // 番地
                        $apply->shipping_address->building, // 建物名
                        $apply->shipping_address->tel, // 電話番号
                        "発送元の名前が入る。", // 名前（発送元）
                        "郵便番号が入る。", // 郵便番号
                        "住所１が入る。", // 住所１
                        "住所２が入る。", // 住所２
                        "電話番号が入る。", // 電話番号
                        "配達希望時間帯が入る。", // 配達希望時間帯
                    ];
                    fputcsv($stream, $fields);
                }
            });
            $targets->update(["apply_status_id" => ApplyStatus::SENT_PRODUCT]);

            Storage::writeStream($filename, $stream);
            fclose($stream);
        }

        return Storage::download($filename);
    }

    private function createProductImage($product, $image_data)
    {
        $file = $image_data["image_file"];
        $file_name = $file->getClientOriginalName();
        $file->move(public_path("uploads/"), $file_name);
        $image = new ProductImage();
        $image->fill([
            "path" => $file_name,
        ]);
        $image->product()->associate($product);
        $image->save();
        return $image;
    }
}
