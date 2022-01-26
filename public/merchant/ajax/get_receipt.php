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

if((int)$kengen == 1){
    $sql = "SELECT * FROM receipts where CAST(status AS SIGNED) >= 20 AND CAST(status AS SIGNED) <= 29 ORDER BY CAST(status AS SIGNED) DESC";
    $stmt = $PDO -> prepare($sql);
    $stmt->execute();

    $json = array();
    $user = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $st = $row["status"];
        if($st == '20') {
            $st = '判断不可';
        } elseif($st == '21') {
            $st = '仮承認';
        } elseif($st == '22') {
            $st = '仮否認';
        } elseif($st == '23') {
            $st = '重複';
        } elseif($st == '24') {
            $st = '不正疑い';
        } elseif($st == '25') {
            $st = '';
        } 
        $user[] = array(
            "user_id" => $row["user_id"], //ユーザーID
            "receipt_no" => $row["no"], //レシートナンバー
            "post_date" => $row["created_at"], //送信日
            "id" => $row["id"], //レシートID
            "status" => $row["mk_status"], //ステータス
            "now_status" => $st, //現在のステータス
            "point" => $row["point"], //ポイント
            "tel" => $row["tel"], //電話番号
            "pay_date" => $row["pay_date"], //レシート日付
            "src" => $row["receipt_path"], //画像URL
            "receipt_value" => $row["receipt_value"], //ユーザー入力の数量
            "products" => $row["products"], //レシートの数量
            "receipt_memo" => $row["receipt_memo"],
            "mk_tel" => $row["mk_tel"],
            "mk_date" => $row["mk_date"],
            "mk_value" => $row["mk_value"],
            "now" => $row["meken_value"],
        );
    }

    $json[] = array(
        "user" => $user
    );
    $result = json_encode($json);

    header('content-type: application/json; charset=utf-8');
    echo $result;
}
else{
    $da = (int)filter_input(INPUT_POST,"date");

    //1ページで割り振る数
    $maxCount = 10;

    //やり残しチェック
    $sql = "SELECT * FROM receipts where mk_user_id = :mk_id AND CAST(status AS SIGNED) >= 10 AND CAST(status AS SIGNED) <= 19 ORDER BY user_id,created_at LIMIT :count";
    $stmt = $PDO -> prepare($sql);
    $stmt->bindValue(':mk_id', $mk_id, PDO::PARAM_STR);
    $stmt->bindValue(':count', $maxCount, PDO::PARAM_INT);
    $stmt->execute();

    //常に割り振った数は、やり残し含め$maxCountになるように
    $count = $stmt->rowCount();
    if($count != $maxCount){
        //まずは割り振り
        $sql = "UPDATE receipts SET mk_user_id = :mk_id where mk_user_id IS NULL AND CAST(status AS SIGNED) >= 10 AND CAST(status AS SIGNED) <= 19 ORDER BY user_id,created_at LIMIT :count"; 
        $stmt = $PDO -> prepare($sql);
        $stmt->bindValue(':mk_id', $mk_id, PDO::PARAM_STR);
        $stmt->bindValue(':count', ($maxCount-$count), PDO::PARAM_INT);
        $stmt->execute();


        $sql = "SELECT * FROM receipts where mk_user_id = :mk_id AND CAST(status AS SIGNED) >= 10 AND CAST(status AS SIGNED) <= 19 ORDER BY user_id,created_at LIMIT :count";
        $stmt = $PDO -> prepare($sql);
        $stmt->bindValue(':mk_id', $mk_id, PDO::PARAM_STR);
        $stmt->bindValue(':count', $maxCount, PDO::PARAM_INT);
        $stmt->execute();
    }

    $json = array();
    $user = array();
    $user_id = "";

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){    
        if($user_id != $row["user_id"]){
            if($user_id != ""){
                $json[] = array(
                    "user" => $user
                );
            }
            $user = array();
            $user_id = $row["user_id"];
        }
        $st = $row["status"];
        if($st == '11') {
            $st = '確認中';
        } elseif($st == '20') {
            $st = '判断不可';
        } elseif($st == '21') {
            $st = '仮承認';
        } elseif($st == '22') {
            $st = '仮否認';
        } elseif($st == '23') {
            $st = '重複';
        } elseif($st == '24') {
            $st = '不正疑い';
        }
        $user[] = array(
            "user_id" => $row["user_id"], //ユーザーID
            "receipt_no" => $row["no"], //レシートナンバー
            "post_date" => $row["created_at"], //送信日
            "id" => $row["id"], //レシートID
            "status" => $row["mk_status"], //ステータス
            "now_status" => $st, //現在のステータス
            "point" => $row["point"], //ポイント
            "tel" => $row["tel"], //電話番号
            "pay_date" => $row["pay_date"], //レシート日付
            "src" => $row["receipt_path"], //画像URL
            "receipt_value" => $row["receipt_value"], //ユーザー入力の数量
            "products" => $row["products"], //レシートの数量
            "receipt_memo" => $row["receipt_memo"],
            "mk_tel" => $row["mk_tel"],
            "mk_date" => $row["mk_date"],
            "mk_value" => $row["mk_value"],
            "now" => $row["meken_value"],
            "now_st" => $row["status"],
        );
    }

    $json[] = array(
        "user" => $user
    );
    $result = json_encode($json);

    header('content-type: application/json; charset=utf-8');
    echo $result;
}

?>

