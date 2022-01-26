<?php
session_start();
try {
    $dsn = 'mysql:dbname=laravel_local;host=db;charset=utf8;';
    $user = 'phper';
    $password = 'secret';
    $PDO = new PDO($dsn, $user, $password);
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('接続できませんでした。' . $e->getMessage());
}
$kengen = $_SESSION['kengen'];
$mk_id = $_SESSION['id'];
$modal_data_no = filter_input( INPUT_POST, "modal_data_no" );
$user_id = filter_input( INPUT_POST, "user_id" );
$modal_receipt_no = filter_input( INPUT_POST, "modal_receipt_no" );
$modal_tel = filter_input( INPUT_POST, "modal_tel" );
$modal_create = filter_input( INPUT_POST, "modal_create" );
$check_oil = filter_input( INPUT_POST, "check_oil" );
$memo = filter_input( INPUT_POST, "memo" );
$action = filter_input( INPUT_POST, "action" );
$st_product = filter_input( INPUT_POST, "st_product" );
$st_oil = filter_input( INPUT_POST, "st_oil" );
$st_input = filter_input( INPUT_POST, "st_input" );
$st_diff = filter_input( INPUT_POST, "st_diff" );
$st_term = filter_input( INPUT_POST, "st_term" );
$st_shop = filter_input( INPUT_POST, "st_shop" );
$st_duplicate = filter_input( INPUT_POST, "st_duplicate" );
$st_ngword = filter_input( INPUT_POST, "st_ngword" );
$st_count = filter_input( INPUT_POST, "st_count" );
$now_status = $st_product.$st_oil.$st_input.$st_diff.$st_term.$st_shop.$st_duplicate.$st_ngword.$st_count;

//仮承認
if($action == 'pre_accept') {
    if(!empty($modal_receipt_no) && !empty($modal_tel) && !empty($modal_create)) {
        $sql = "SELECT * FROM receipts WHERE NOT (id = :modal_data_no) AND no = :modal_receipt_no AND tel = :modal_tel AND pay_date = :modal_create";
        $stmt = $PDO -> prepare($sql);
        $stmt->bindValue(':modal_tel', $modal_tel, PDO::PARAM_STR);
        $stmt->bindValue(':modal_receipt_no', $modal_receipt_no, PDO::PARAM_STR);
        $stmt->bindValue(':modal_create', $modal_create, PDO::PARAM_STR);
        $stmt->bindValue(':modal_data_no', $modal_data_no, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt -> rowCount() > 0){
            header('content-type: application/json; charset=utf-8');
            echo json_encode(array(
                "result" => false,
                "double" => true,
                "reason" => "重複したレシートです。"
            ));
            exit();
        }
    }
    $sql = "UPDATE receipts SET
            status = '21',
            receipt_memo = :memo,
            mk_no = :modal_receipt_no,
            mk_tel = :modal_tel,
            mk_date = :modal_create,
            mk_value = :check_oil,
            meken_value = '$now_status'
            WHERE id = :modal_data_no";
    $stmt = $PDO -> prepare($sql);
    $stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
    $stmt->bindValue(':modal_receipt_no', $modal_receipt_no, PDO::PARAM_STR);
    $stmt->bindValue(':modal_tel', $modal_tel, PDO::PARAM_STR);
    $stmt->bindValue(':modal_create', $modal_create, PDO::PARAM_STR);
    $stmt->bindValue(':check_oil', $check_oil, PDO::PARAM_STR);
    $stmt->bindValue(':modal_data_no', $modal_data_no, PDO::PARAM_STR);
    $stmt->execute();
    header('content-type: application/json; charset=utf-8');
    echo json_encode(array(
        "result" => 'pre_accept',
        "back_id" => $modal_data_no,
        "reason" => "仮承認しました。"
    ));
}

//仮否認
if($action == 'pre_reject') {
    if(!empty($modal_receipt_no) && !empty($modal_tel) && !empty($modal_create)) {
        $sql = "SELECT * FROM receipts WHERE NOT (id = :modal_data_no) AND no = :modal_receipt_no AND tel = :modal_tel AND pay_date = :modal_create";
        $stmt = $PDO -> prepare($sql);
        $stmt->bindValue(':modal_tel', $modal_tel, PDO::PARAM_STR);
        $stmt->bindValue(':modal_receipt_no', $modal_receipt_no, PDO::PARAM_STR);
        $stmt->bindValue(':modal_create', $modal_create, PDO::PARAM_STR);
        $stmt->bindValue(':modal_data_no', $modal_data_no, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt -> rowCount() > 0){
            $sql = "UPDATE receipts SET
                    status = '23',
                    receipt_memo = :memo,
                    mk_no = :modal_receipt_no,
                    mk_tel = :modal_tel,
                    mk_date = :modal_create,
                    mk_value = :check_oil,
                    meken_value = '$now_status'
                    WHERE id = :modal_data_no";
            $stmt = $PDO -> prepare($sql);
            $stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
            $stmt->bindValue(':modal_receipt_no', $modal_receipt_no, PDO::PARAM_STR);
            $stmt->bindValue(':modal_tel', $modal_tel, PDO::PARAM_STR);
            $stmt->bindValue(':modal_create', $modal_create, PDO::PARAM_STR);
            $stmt->bindValue(':check_oil', $check_oil, PDO::PARAM_STR);
            $stmt->bindValue(':modal_data_no', $modal_data_no, PDO::PARAM_STR);
            $stmt->execute();
            header('content-type: application/json; charset=utf-8');
            echo json_encode(array(
                "result" => 'pre_reject_duplicate',
                "back_id" => $modal_data_no,
                "reason" => "仮否認したレシートは重複したレシートでした。"
            ));
            exit();
        }
    }
    $sql = "UPDATE receipts SET
            status = '22',
            receipt_memo = :memo,
            mk_no = :modal_receipt_no,
            mk_tel = :modal_tel,
            mk_date = :modal_create,
            mk_value = :check_oil,
            meken_value = '$now_status'
            WHERE id = :modal_data_no";
    $stmt = $PDO -> prepare($sql);
    $stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
    $stmt->bindValue(':modal_receipt_no', $modal_receipt_no, PDO::PARAM_STR);
    $stmt->bindValue(':modal_tel', $modal_tel, PDO::PARAM_STR);
    $stmt->bindValue(':modal_create', $modal_create, PDO::PARAM_STR);
    $stmt->bindValue(':check_oil', $check_oil, PDO::PARAM_STR);
    $stmt->bindValue(':modal_data_no', $modal_data_no, PDO::PARAM_STR);
    $stmt->execute();
    header('content-type: application/json; charset=utf-8');
    echo json_encode(array(
        "result" => 'pre_reject',
        "back_id" => $modal_data_no,
        "reason" => "仮否認しました。"
    ));
}

//判断不可
if($action == 'pre_confirm') {
    $sql = "UPDATE receipts SET
            status = '20',
            receipt_memo = :memo,
            mk_no = :modal_receipt_no,
            mk_tel = :modal_tel,
            mk_date = :modal_create,
            mk_value = :check_oil,
            meken_value = '$now_status'
            WHERE id = :modal_data_no";
    $stmt = $PDO -> prepare($sql);
    $stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
    $stmt->bindValue(':modal_receipt_no', $modal_receipt_no, PDO::PARAM_STR);
    $stmt->bindValue(':modal_tel', $modal_tel, PDO::PARAM_STR);
    $stmt->bindValue(':modal_create', $modal_create, PDO::PARAM_STR);
    $stmt->bindValue(':check_oil', $check_oil, PDO::PARAM_STR);
    $stmt->bindValue(':modal_data_no', $modal_data_no, PDO::PARAM_STR);
    $stmt->execute();
    header('content-type: application/json; charset=utf-8');
    echo json_encode(array(
        "result" => 'pre_confirm',
        "back_id" => $modal_data_no,
        "reason" => "判断不可で送信しました。"
    ));
}

//不正疑い
if($action == 'pre_illegal') {
    $sql = "UPDATE receipts SET
            status = '24',
            receipt_memo = :memo,
            mk_no = :modal_receipt_no,
            mk_tel = :modal_tel,
            mk_date = :modal_create,
            mk_value = :check_oil,
            meken_value = '$now_status'
            WHERE id = :modal_data_no";
    $stmt = $PDO -> prepare($sql);
    $stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
    $stmt->bindValue(':modal_receipt_no', $modal_receipt_no, PDO::PARAM_STR);
    $stmt->bindValue(':modal_tel', $modal_tel, PDO::PARAM_STR);
    $stmt->bindValue(':modal_create', $modal_create, PDO::PARAM_STR);
    $stmt->bindValue(':check_oil', $check_oil, PDO::PARAM_STR);
    $stmt->bindValue(':modal_data_no', $modal_data_no, PDO::PARAM_STR);
    $stmt->execute();
    header('content-type: application/json; charset=utf-8');
    echo json_encode(array(
        "result" => 'pre_illegal',
        "back_id" => $modal_data_no,
        "reason" => "不正疑いで送信しました。"
    ));
}
?>

