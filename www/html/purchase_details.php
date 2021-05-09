<?php
//購入履歴ページ
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'purchase_details.php';

session_start();
// ログイン状態が確認できない場合はログイン画面に飛ぶ
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

// トークンがPOSTの値とセッションの値で同一であるか調べ、
// 検証後、セッションを破棄し、違っていればメッセージセット＆引数のページに飛ぶ
is_valid_csrf_token_check(PURCHASE_URL);

// データベースの接続を確立する
$db = get_db_connect();
// ログインしているユーザーデータを返す
$user = get_login_user($db);

// 押されたボタンの注文番号を取得
$order_id = get_post('order_id');
// order_idを使って購入明細情報の取得
$purchase_details = get_purchase_details($db,$user['user_id'],$order_id);
// order_idを使って購入履歴の注文番号一つのみを取得
$purchase_detail = get_purchase_detail($db,$user['user_id'],$order_id);

// 二次元配列の特殊文字をHTMLエンティティにする
$purchase_details = entity_change($purchase_details);
// 一次元配列の特殊文字をHTMLエンティティにする
$purchase_detail = entity_change_one($purchase_detail);

// トークンを取得する
get_csrf_token();
// htmlページを表示
include VIEW_PATH . 'purchase_details_view.php';