<?php
session_start();

if(empty($_SESSION['id'])) {
    header('Location:./auth_login.php');
    exit;
}
else{
    $id = $_SESSION['id'];
    $kengen = $_SESSION['kengen'];
}
?>
<!DOCTYPE html>
<html lang="ja">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
<title>目検</title>
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="./css/style.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css" />
</html>
<body>
<input type="hidden" id="user_id" value="<?= $id; ?>">
<input type="hidden" id="user_kengen" value="<?= $kengen; ?>">
<div class="header-container">
        <header class="header navbar navbar-expand-sm">

            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

            <div class="nav-logo align-self-center">
            <a class="navbar-brand" href="/sales"><span class="navbar-brand-name">レシート一覧</span></a>
            </div>

            <ul class="navbar-item dropdown flex-row nav-dropdowns ">
                <?php
                if($_SESSION['kengen'] == 1){
                echo '<li class="nav-item user-profile-dropdown order-lg-0 order-1">
                        <a href="./users.php" class="nav-link user" aria-haspopup="true" aria-expanded="false">
                            <div class="media">
                                <div class="media-body align-self-center">
                                    <h6>ユーザー管理</h6>
                                </div>
                            </div>
                        </a>
                      </li>';
                }
                ?>
                
                <li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="user-profile-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media">
                            <div class="media-body align-self-center">
                                <h6><?php echo $id; ?></h6>
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="user-profile-dropdown">
                        <div class="">
                            <div class="dropdown-item">
                                <a href="http://localhost:8080/test/admin/logout" onclick="event.preventDefault();
                                            location.href='./auth_logout.php';"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> ログアウト</a>
                            </div>
                        </div>
                    </div>

                </li>
            </ul>
        </header>
    </div>

    <div id="content" class="main-content">
        
        <div class="layout-px-spacing">
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                    <div class="widget-content widget-content-area br-6">
                        <div class="table-responsive mb-4 mt-4">
                            <table id="zero-config" class="table table-hover user_list" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>応募者ID</th>
                                        <th>送信日時</th>
                                        <th>レシートID</th>
                                        <th>状況</th>
                                        <th>ステータス</th>
                                    </tr>
                                </thead>
                                <tbody id="mk_tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div id="modal">
        <div class="modal_container">
            <div class="modal_inner">
                <div class="modal_img_container">
                    <img id="receipt_img" src="../assets/img/extention.png">
                    <img id="zoomImg">
                </div>
            </div>
            <div class="modal_inner">
                <b>このレシートの確認項目</b>
                <table class="status_table table">
                    <tr>
                        <td class="product" data-st="product" data-now="0">対象商品</td>
                        <td class="oil" data-st="oil" data-now="0">給油量</td>
                        <td class="input" data-st="input" data-now="0">自己申告</td>
                    </tr>
                    <tr>
                        <td class="diff" data-st="diff" data-now="0">給油量の差</td>
                        <td class="term" data-st="term" data-now="0">対象期間</td>
                        <td class="shop" data-st="shop" data-now="0">対象店舗</td>
                    </tr>
                    <tr>
                        <td class="duplicate" data-st="duplicate" data-now="0">重複</td>
                        <td class="ngword" data-st="ngword" data-now="0">NGワード</td>
                        <td class="count" data-st="count" data-now="0">強制送信</td>
                    </tr>
                </table>
                <div id="status_detail"></div><!-- 追加 -->
                <!-- <h3 id="status_title"></h2>
                <p id="status_reason"></p> -->
                <div class="modal_status">
                    <div class="cf"></div>
                    <form action="" class="mt-4" onSubmit="onOkButton();return false;">
                        <p id="modal_data_no" style="display:none;"></p>
                        <p id="modal_user_id" style="display:none;"></p>
                        <b>重複チェック・期間チェック</b>
                        <table class="table result_table">
                            <tr>
                                <td>レシートNo.</td>
                                <td>電話番号</td>
                                <td>レシート日付</td>
                            </tr>
                            <tr>
                                <td><input type="text" id="modal_receipt_no" name="modal_receipt_no"></td>
                                <td><input type="text" id="modal_tel" name="modal_tel"></td>
                                <td><input type="text" id="modal_create" name="modal_create"></td>
                            </tr>
                        </table>

                        <b>数量チェック</b>
                        <table class="table result_value">
                            <tr>
                                <td>レシート上の数量</td>
                                <td>自己申告の数量</td>
                                <td>確認後の数量</td>
                            </tr>
                            <tr>
                                <td class="receipt_oil_wrap"><span id="receipt_oil"></span></td>
                                <td class="self_oil_wrap"><span id="self_oil"></span></td>
                                <td><input type="text" id="check_oil" name="check_oil" value="">L</td>
                            </tr>
                        </table>
                        <p>メモ</p>
                        <textarea name="memo" id="memo" cols="30" rows="3"></textarea>
                        <div class="cf"></div>
                        <input type="hidden" id="now_st" value="">
                        <input type="hidden" id="modal_status" name="modal_status" value="">
                        <input type="hidden" id="st_product" name="st_product" value="0">
                        <input type="hidden" id="st_oil" name="st_oil" value="0">
                        <input type="hidden" id="st_input" name="st_input" value="0">
                        <input type="hidden" id="st_diff" name="st_diff" value="0">
                        <input type="hidden" id="st_term" name="st_term" value="0">
                        <input type="hidden" id="st_shop" name="st_shop" value="0">
                        <input type="hidden" id="st_duplicate" name="st_duplicate" value="0">
                        <input type="hidden" id="st_ngword" name="st_ngword" value="0">
                        <input type="hidden" id="st_count" name="st_count" value="0">
                        <?php
                            if($kengen == 1) {
                                $btns = '
                                    <div class="flex mt-3">
                                        <input type="button" id="accept" name="accept" class="btn-success submit" value="承認">
                                        <input type="button" id="reject" name="reject" class="btn-info submit" value="否認">
                                        <input type="button" id="confirm" name="confirm" class="btn-secondary submit" value="判断不可">
                                        <input type="button" id="illegal" name="illegal" class="btn-warning submit" value="不正">
                                    </div>
                                ';
                            } else {
                                $btns = '
                                <div class="flex mt-3">
                                    <input type="button" id="pre_accept" name="pre_accept" class="btn-success pre_submit" value="仮承認">
                                    <input type="button" id="pre_reject" name="pre_reject" class="btn-info pre_submit" value="仮否認">
                                    <input type="button" id="pre_confirm" name="pre_confirm" class="btn-secondary pre_submit" value="判断不可">
                                    <input type="button" id="pre_illegal" name="pre_illegal" class="btn-warning pre_submit" value="不正疑い">
                                </div>
                                ';
                            }
                        ?>
                        <?= $btns; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="../assets/js/libs/jquery-3.1.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="./js/ajax.js"></script>
<script src="./js/main.js"></script>

</body>
</html>
