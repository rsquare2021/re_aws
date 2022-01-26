<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware("auth:admin")->get("/", "Admin\CampaignController@index");

// 管理業務
Route::namespace("Admin")->name("admin.")->prefix("admin")->group(function() {

    // ログイン
    Auth::routes([
        "register" => false,
        "reset" => true,
        "verify" => false,
    ]);

    Route::middleware("auth:admin")->group(function() {
        Route::get("/", "CampaignController@index")->name("home");

        // NextTube、FLINT、Rスクエアのみアクセスできる。
        Route::middleware("can:developer")->group(function() {
            Route::middleware("can:super_admin")->group(function() {
                // 会社
                // Route::resource("company", "CompanyController", ["only" => ["index", "create", "store", "edit", "update"]]);
                // 店舗ツリー
                Route::resource("shop_tree", "ShopTreeController", ["only" => ["create", "store", "destroy"]]);
                // 景品
                Route::resource("product", "ProductController", ["only" => ["index", "create", "store", "edit", "update"]]);
                Route::get("product/{id}/delete", "ProductController@destroy")->name("product.destroy");
                Route::get("product/csv", "ProductController@uploadCsv")->name("product.upload");
                Route::post("product/csv", "ProductController@importCsv")->name("product.import");
                // 景品カテゴリー
                Route::resource("product/product_cat", "ProductCategoryController", ["only" => ["index", "create", "store", "edit", "update"]]);
                // 景品プリセット
                Route::resource("product/preset", "ProductPresetController", ["only" => ["index", "create", "store", "edit", "update", "destroy"]]);
            });
        });

        // スーパー管理ユーザーのみアクセスできる。
        Route::middleware("can:super_admin")->group(function() {
            // 管理ユーザー
            Route::resource("user", "AccountController", ["only" => ["index", "create", "store", "edit", "update", "destroy"]]);
            Route::middleware("can:editable_admin,user")->group(function() {
                Route::get('user/{user}/charge/edit', "AccountController@charge")->name("user.charge.edit");
                Route::put("user/{user}/charge/", "AccountController@updateCharge")->name("user.charge.update");
            });

            // 店舗ツリー
            Route::resource("shop_tree", "ShopTreeController", ["only" => ["edit", "update"]]);
            Route::middleware("can:editable_shop_tree_element,Shop_tree")->group(function() {
                Route::get("shop_tree/{Shop_tree}/download", "ShopTreeController@download")->name("shop_tree.download");
                // 店舗情報
                Route::get("shop/{Shop_tree}/edit", "ShopTreeController@editShop")->name("shop.edit");
                Route::post("shop/{Shop_tree}/update", "ShopTreeController@updateShop")->name("shop.update");
            });

            // キャンペーン
            Route::resource("project", "CampaignController", ["only" => ["create", "store"]]);
            Route::middleware("can:editable_campaign,project")->group(function() {
                Route::get("/project/{project}/copy", "CampaignController@createCopy")->name("project.copy.create");
                Route::post("/project/{project}/copy", "CampaignController@storeCopy")->name("project.copy.store");
                // キャンペーン抽選
                Route::get("/project/{project}/lottery", "LotteryController@execute")->name("project.lottery");
            });
            Route::get("/downloadShippingCsv/{yyyymmdd}", "ProductController@exportShippingCsv")->name("project.download_shipping_csv");
        });
        
        // 一般管理ユーザー・スーパー管理ユーザーがアクセスできる。
        Route::middleware("can:regular_admin")->group(function() {
            // キャンペーン
            Route::resource("project", "CampaignController", ["only" => ["index", "edit", "update"]]);
            Route::middleware("can:editable_campaign,project")->group(function() {
                // キャンペーン店舗確認・編集
                Route::get("/project/{project}/shop/edit", "CampaignController@editShops")->name("project.shop.edit");
                Route::put("/project/{project}/shop", "CampaignController@updateShops")->name("project.shop.update");
                // キャンペーン景品追加
                Route::get("/project/{project}/product/lottery/edit", "CampaignProductController@editLotteryGift")->name("project.product.edit.lottery");
                Route::put("/project/{project}/product/lottery", "CampaignProductController@updateLotteryGift")->name("project.product.update.lottery");
                Route::match(["get", "put"], "/project/{project}/product/catalog/edit", "CampaignProductController@editCatalogGift")->name("project.product.edit.catalog");
                Route::put("/project/{project}/product/catalog", "CampaignProductController@updateCatalogGift")->name("project.product.update.catalog");
                // Route::get("/project/{project}/product/create", "CampaignProductController@create")->name("project.product.create");
            });
        });

        // プロフィール
        Route::get("/profile", "AccountController@editProfile")->name("profile.edit");
        Route::put("/profile", "AccountController@updateProfile")->name("profile.update");
    });
});

    // セッション破棄
    Route::get('/test/flush', function () {
        session()->flush();
        return 'セッションを破棄しました。<a href="http://localhost:8080/test/campaign/513/login">ログインテスト</>';
    });

    // 権限一覧
    Route::get('/test/admin/user/role', function() {
        $data = [
            'category_name' => 'user',
            'page_name' => 'user_role',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '権限一覧',
        ];
        return view('admin.shop_tree._create')->with($data);
    });
    // 権限追加
    Route::get('/test/admin/user/role/create', function() {
        $data = [
            'category_name' => 'user',
            'page_name' => 'user_role_create',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '権限追加',
        ];
        return view('admin.user.role_create')->with($data);
    });
    // ユーザー店舗確認・編集 *不要
    Route::get('/test/admin/user/shop', function() {
        $data = [
            'category_name' => 'user',
            'page_name' => 'user_shop',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'ユーザー店舗確認・編集',
        ];
        return view('admin.user.shop')->with($data);
    });

    // 店舗管理
    // 店舗一覧
    Route::get('/test/admin/shop', function() {
        $data = [
            'category_name' => 'shop',
            'page_name' => 'shop',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '店舗一覧',
        ];
        return view('admin.shop.list')->with($data);
    });
    // 店舗追加
    Route::get('/test/admin/shop/create', function() {
        $data = [
            'category_name' => 'shop',
            'page_name' => 'shop_create',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '店舗追加',
        ];
        return view('admin.shop.create')->with($data);
    });
    // 店舗編集
    Route::get('/test/admin/shop/edit', function() {
        $data = [
            'category_name' => 'shop',
            'page_name' => 'shop_edit',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '店舗編集',
        ];
        return view('admin.shop.edit')->with($data);
    });
    // 店舗エリア一覧
    Route::get('/test/admin/shop/area', function() {
        $data = [
            'category_name' => 'shop',
            'page_name' => 'shop_area',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '店舗エリア一覧',
        ];
        return view('admin.shop.area')->with($data);
    });
    // 店舗エリア追加
    Route::get('/test/admin/shop/area/create', function() {
        $data = [
            'category_name' => 'shop',
            'page_name' => 'shop_area_create',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '店舗エリア追加',
        ];
        return view('admin.shop.area_create')->with($data);
    });
    // 店舗プリセット一覧
    Route::get('/test/admin/shop/preset', function() {
        $data = [
            'category_name' => 'shop',
            'page_name' => 'shop_preset',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '店舗プリセット一覧',
        ];
        return view('admin.shop.preset')->with($data);
    });
    // 店舗プリセット追加
    Route::get('/test/admin/shop/preset/create', function() {
        $data = [
            'category_name' => 'shop',
            'page_name' => 'shop_preset_create',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '店舗プリセット追加',
        ];
        return view('admin.shop.preset_create')->with($data);
    });
    // 店舗プリセット編集
    Route::get('/test/admin/shop/preset/edit', function() {
        $data = [
            'category_name' => 'shop',
            'page_name' => 'shop_preset_edit',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '店舗プリセット編集',
        ];
        return view('admin.shop.preset_edit')->with($data);
    });

    // 応募管理
    // 応募一覧
    Route::get('/test/admin/project/apply', function() {
        $data = [
            'category_name' => 'project',
            'page_name' => 'apply',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '応募一覧',
        ];
        return view('admin.project.apply.list')->with($data);
    });

    // ポイントカテゴリー一覧
    Route::get('/test/admin/product/point_cat', function() {
        $data = [
            'category_name' => 'product',
            'page_name' => 'point_cat',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '景品カテゴリー追加',
        ];
        return view('admin.product.point_cat')->with($data);
    });
    // ポイントカテゴリー追加
    Route::get('/test/admin/product/point_cat/create', function() {
        $data = [
            'category_name' => 'product',
            'page_name' => 'point_cat_create',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'ポイントカテゴリー追加',
        ];
        return view('admin.product.point_cat_create')->with($data);
    });

    // マスター管理
    // 抽選権利
    Route::get('/test/admin/dev/right', function() {
        $data = [
            'category_name' => 'dev',
            'page_name' => 'right',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '抽選権利',
        ];
        return view('admin.dev.right')->with($data);
    });
    // 抽選権利追加
    Route::get('/test/admin/dev/right/create', function() {
        $data = [
            'category_name' => 'dev',
            'page_name' => 'right_create',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '抽選権利追加',
        ];
        return view('admin.dev.right_create')->with($data);
    });
    // 抽選タイミング
    Route::get('/test/admin/dev/timing', function() {
        $data = [
            'category_name' => 'dev',
            'page_name' => 'timing',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '抽選タイミング',
        ];
        return view('admin.dev.timing')->with($data);
    });
    // 抽選タイミング追加
    Route::get('/test/admin/dev/timing/create', function() {
        $data = [
            'category_name' => 'dev',
            'page_name' => 'timing_create',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => '抽選タイミング追加',
        ];
        return view('admin.dev.timing_create')->with($data);
    });
    // テーマカラー
    Route::get('/test/admin/dev/theme', function() {
        $data = [
            'category_name' => 'dev',
            'page_name' => 'theme',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'テーマカラー',
        ];
        return view('admin.dev.theme')->with($data);
    });
    // テーマカラー追加
    Route::get('/test/admin/dev/theme/create', function() {
        $data = [
            'category_name' => 'dev',
            'page_name' => 'theme_create',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'テーマカラー追加',
        ];
        return view('admin.dev.theme_create')->with($data);
    });

    // 管理者用エンドユーザー一覧
    Route::get('/test/admin/enduser', function() {
        $data = [
            'category_name' => 'enduser',
            'page_name' => 'enduser',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'エンドユーザー一覧',
        ];
        return view('admin.enduser.list')->with($data);
    });
    // 管理者用エンドユーザー応募一覧
    Route::get('/test/admin/enduser/apply', function() {
        $data = [
            'category_name' => 'enduser',
            'page_name' => 'enduser_apply',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'エンドユーザー応募',
        ];
        return view('admin.enduser.apply')->with($data);
    });

    // レシート管理
    // レシート一覧
    Route::get('/test/admin/re', function() {
        $data = [
            'category_name' => 're',
            'page_name' => 're',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'レシート一覧',
        ];
        return view('admin.re.list')->with($data);
    });



/////////////////////////////////////////////////////////////
// エンドユーザーここから
/////////////////////////////////////////////////////////////
Route::namespace("User")->name("campaign.")->group(function() {
    // 1_1_ログイン & キャンペーン概要 & 不参加のリダイレクト先
    Route::get('/{campaign_id}', "Auth\LoginController@showLoginForm")->name("login");
    Route::post("/{campaign_id}", "Auth\LoginController@login");
    Route::post("/{campaign_id}/logout", "Auth\LoginController@logout")->name("logout");
    // 2_1_アカウント作成（選択画面）
    Route::get('/{campaign_id}/signup', "Auth\RegisterController@selectRegistrationMethod")->name("register.method");
    // 2_2_アカウント作成（メール：入力）
    Route::get('/{campaign_id}/signup/mail', "Auth\RegisterController@showRegistrationForm")->name("register");
    Route::post("/{campaign_id}/signup/mail", "Auth\RegisterController@register")->name("register");
    // 2_4_アカウント仮登録完了（メール）
    Route::get('/{campaign_id}/signup/mail/pre_complete', "Auth\RegisterController@precomplete")->name("register.precomplete");
    // 2_5_アカウント登録完了（共通）
    Route::get('/{campaign_id}/signup/{method}/complete', "Auth\VerificationController@complete")->where(["method" => "register|update"])->name("verification.complete");
    // 2_6_パスワード再発行
    Route::get('/{campaign_id}/password/reset', "Auth\ForgotPasswordController@showLinkRequestForm")->name("password.request");
    // 2_7_パスワード再発行URL送信画面
    Route::post("/{campaign_id}/password/email", "Auth\ForgotPasswordController@sendResetLinkEmail")->name("password.email");
    // 2_8_新パスワード入力
    Route::get("/{campaign_id}/password/reset/{token}", "Auth\ResetPasswordController@showResetForm")->name("password.reset");
    Route::post("/{campaign_id}/password/reset", "Auth\ResetPasswordController@reset")->name("password.update");
    // 2_9_メールアドレス設定 & 変更
    Route::get('/{campaign_id}/mail/change', "Auth\VerificationController@editEmail")->name("email.edit");
    // 2_10_メールアドレス確認URL送信画面
    Route::put("/{campaign_id}/mail/change/send", "Auth\VerificationController@updateEmail")->name("email.update");
    Route::get("/{campaign_id}/email/verify/{method}/{id}/{hash}", "Auth\VerificationController@verify")->where(["method" => "register|update"])->name("verification.verify");
    // Route::get("/{campaign_id}/email/verify", "Auth\VerificationController@show")->name("verification.notice");
    // Route::post("/{campaign_id}/email/resend", "Auth\VerificationController@resend")->name("verification.resend");
    // 6_1_景品一覧（ポイントカタログ）
    Route::get('/{campaign_id}/list', "CatalogGiftController@pre_list")->name("top.catalog.gift.index");
    // 6_2_景品詳細（ポイントカタログ）
    Route::get('/{campaign_id}/list/{gift_id}', "CatalogGiftController@pre_show")->name("top.catalog.gift.show");
    // 9_1_よくある質問
    Route::get('/{campaign_id}/faq', "CampaignGuideController@faq")->name("faq");
    // 9_2_当サイトのご利用にあたって
    Route::get('/{campaign_id}/guide', "CampaignGuideController@guide")->name("guide");
    // プライバシーポリシー
    Route::get('/{campaign_id}/privacypolicy', "CampaignGuideController@privacypolicy")->name("privacypolicy");
});

Route::namespace("User")->group(function() {
    // LINEログイン
    Route::get("/{campaign_id}/login/{provider}", "Auth\LoginController@redirectToProvider")->name("campaign.login.provider");
    Route::get("/login/{provider}/callback", "Auth\LoginController@handleProviderCallback");
});

Route::middleware("auth:user", "verified")->namespace("User")->name("campaign.")->group(function() {
    // 2_11_キャンペーン参加
    Route::get('/{campaign_id}/confirm', "Auth\LoginController@confirmToJoinCampaign")->name("entry");
    Route::post("/{campaign_id}/confirm", "Auth\LoginController@joinCampaign");
});

Route::middleware("auth:user", "joined")->namespace("User")->name("campaign.")->group(function() {
    // 3_1_ダッシュボード
    Route::get('/{campaign_id}/dashboard', "HomeController@dashboard")->name("dashboard");
    // 5_1_レシート送信履歴
    Route::get('/{campaign_id}/dashboard/serial', "ReceiptController@index")->name("receipt.index");
    // 6_1_景品一覧（ポイントカタログ）
    Route::get('/{campaign_id}/dashboard/point/gift', "CatalogGiftController@index")->name("catalog.gift.index");
    // 6_2_景品詳細（ポイントカタログ）
    Route::get('/{campaign_id}/dashboard/point/gift/{gift_id}', "CatalogGiftController@show")->name("catalog.gift.show");
    // 7_1_お問い合わせ
    Route::get('/{campaign_id}/inquiry', 'ContactController@index')->name('contact.index');
    // 7_2_お問い合わせ確認
    Route::post('/{campaign_id}/inquiry/confirm', 'ContactController@confirm')->name('contact.confirm');
    // 7_3_お問い合わせ完了
    Route::post('/{campaign_id}/inquiry/thanks', 'ContactController@send')->name('contact.send');
    // 8_1_レシート撮影カメラ画面
    Route::get('/{campaign_id}/dashboard/snap', "ReceiptController@snap")->name("receipt.snap");
});

Route::middleware("auth:user", "verified", "joined")->namespace("User")->name("campaign.")->group(function() {
    // 2_11_パスワード登録&変更画面
    Route::get("/{campaign_id}/password/change", "Auth\ChangePasswordController@index")->name("password.change");
    // 2_12_パスワード登録&変更画面
    Route::post("/{campaign_id}/password/change/send", "Auth\ChangePasswordController@update")->name("password.change.update");
    // 4_1_交換履歴一覧
    Route::get('/{campaign_id}/dashboard/exchange', "ApplyHistoryController@index")->name("apply.index");
    // 4_3_交換履歴発送先編集
    Route::get('/{campaign_id}/dashboard/exchange/{exchange_id}/edit', "ApplyHistoryController@editAddress")->name("apply.address.edit");
    // 4_5_交換履歴発送先編集完了
    Route::put("/{campaign_id}/dashboard/exchange/{exchange_id}", "ApplyHistoryController@updateAddress")->name("apply.address.update");
    // 4_6_交換履歴キャンセル確認
    Route::get('/{campaign_id}/dashboard/exchange/{exchange_id}/cancel/confirm', "ApplyHistoryController@confirmToCancelApply")->name("apply.cancel.confirm");
    // 4_7_交換履歴キャンセル完了
    Route::post('/{campaign_id}/dashboard/exchange/{exchange_id}/cancel', "ApplyHistoryController@cancelApply")->name("apply.cancel");
    // 6_3_発送先の入力（ポイントカタログ型）
    Route::post('/{campaign_id}/dashboard/point/gift/{gift_id}', "CatalogApplyController@setProductToApply")->name("catalog.gift.apply.set_product");
    Route::get('/{campaign_id}/dashboard/point/gift/{gift_id}/order', "CatalogApplyController@editAddressToApply")->name("catalog.gift.apply.address");
    // 6_5_景品交換完了（ポイントカタログ交換）
    Route::post('/{campaign_id}/dashboard/point/gift/{gift_id}/order/complete', "CatalogApplyController@setAddressToApply")->name("catalog.gift.apply.set_address");
});
/////////////////////////////////////////////////////////////
// エンドユーザーここまで
/////////////////////////////////////////////////////////////






    Route::get('/test/{campaign_id}/{user_id}/mypage/snap/before', function() {
        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'snap',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'レシート撮影画面（注意事項）',
        ];
        return view('user.snap_before')->with($data);
    });
    // Route::get('/{campaign_id}/dashboard/snap', function() {
    //     $data = [
    //         'category_name' => 'dashboard',
    //         'page_name' => 'snap',
    //         'has_scrollspy' => 0,
    //         'scrollspy_offset' => '',
    //         'title' => 'レシート撮影',
    //     ];
    //     return view('user.snap')->with($data);
    // });
    // レシート撮影確認画面
    Route::get('/test/{campaign_id}/{user_id}/mypage/snap/confirm', function($campaign_id) {
        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'snap_confirm',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'レシート撮影確認画面',
        ];
        return view('user.snap_confirm')->with($data);
    });
    // レシート送信後画面
    Route::get('/test/{campaign_id}/{user_id}/mypage/snap/complete', function() {
        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'snap_complete',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'レシート送信後画面',
        ];
        return view('user.snap_complete')->with($data);
    });



    // $this->middleware

    Route::get('/analytics', function() {
        // $category_name = '';
        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'analytics',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];
        // $pageName = 'analytics';
        return view('dashboard')->with($data);
    });
    
    Route::get('/sales', function() {
        // $category_name = '';
        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'sales',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'title' => 'タイトル',
        ];
        // $pageName = 'sales';
        return view('dashboard2')->with($data);
    });

    // Authentication
    Route::prefix('authentication')->group(function () {
        Route::get('/lockscreen_boxed', function() {
            // $category_name = 'auth';
            $data = [
                'category_name' => 'auth',
                'page_name' => 'auth_boxed',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
            ];
            // $pageName = 'auth_boxed';
            return view('pages.authentication.auth_lockscreen_boxed')->with($data);
        });
        Route::get('/lockscreen', function() {
            // $category_name = 'auth';
            $data = [
                'category_name' => 'auth',
                'page_name' => 'auth_default',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
            ];
            // $pageName = 'auth_default';
            return view('pages.authentication.auth_lockscreen')->with($data);
        });
        Route::get('/login_boxed', function() {
            // $category_name = 'auth';
            $data = [
                'category_name' => 'auth',
                'page_name' => 'auth_boxed',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
            ];
            // $pageName = 'auth_boxed';
            return view('pages.authentication.auth_login_boxed')->with($data);
        });
        Route::get('/login', function() {
            // $category_name = 'auth';
            $data = [
                'category_name' => 'auth',
                'page_name' => 'auth_default',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
            ];
            // $pageName = 'auth_default';
            return view('pages.authentication.auth_login')->with($data);
        });
        Route::get('/pass_recovery_boxed', function() {
            // $category_name = 'auth';
            $data = [
                'category_name' => 'auth',
                'page_name' => 'auth_boxed',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
            ];
            // $pageName = 'auth_boxed';
            return view('pages.authentication.auth_pass_recovery_boxed')->with($data);
        });
        Route::get('/pass_recovery', function() {
            // $category_name = 'auth';
            $data = [
                'category_name' => 'auth',
                'page_name' => 'auth_default',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
            ];
            // $pageName = 'auth_default';
            return view('pages.authentication.auth_pass_recovery')->with($data);
        });
        Route::get('/register_boxed', function() {
            // $category_name = 'auth';
            $data = [
                'category_name' => 'auth',
                'page_name' => 'auth_boxed',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
            ];
            // $pageName = 'auth_boxed';
            return view('pages.authentication.auth_register_boxed')->with($data);
        });
        Route::get('/register', function() {
            // $category_name = 'auth';
            $data = [
                'category_name' => 'auth',
                'page_name' => 'auth_default',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
            ];
            // $pageName = 'auth_default';
            return view('pages.authentication.auth_register')->with($data);
        });
    });

// レシート目検業者
// リスト
// Route::get('/merchant', function() {
//     $data = [
//         'category_name' => 'merchant',
//         'page_name' => 'merchant',
//         'has_scrollspy' => 0,
//         'scrollspy_offset' => '',
//         'title' => '目検リスト',
//     ];
//     return view('merchant.list')->with($data);
// });
// // ログイン
// Route::get('/merchant/login', function() {
//     $data = [
//         'category_name' => 'merchant',
//         'page_name' => 'merchant',
//         'has_scrollspy' => 0,
//         'scrollspy_offset' => '',
//         'title' => '目検業者ログイン',
//     ];
//     return view('publi')->with($data);
// });
// // ユーザー一覧
// Route::get('/merchant/user', function() {
//     $data = [
//         'category_name' => 'merchant',
//         'page_name' => 'merchant',
//         'has_scrollspy' => 0,
//         'scrollspy_offset' => '',
//         'title' => '目検業者ユーザー一覧',
//     ];
//     return view('merchant.user')->with($data);
// });
// // ユーザー登録
// Route::get('/merchant/user/regist', function() {
//     $data = [
//         'category_name' => 'merchant',
//         'page_name' => 'merchant',
//         'has_scrollspy' => 0,
//         'scrollspy_offset' => '',
//         'title' => '目検業者ユーザー登録',
//     ];
//     return view('merchant.user_regist')->with($data);
// });
