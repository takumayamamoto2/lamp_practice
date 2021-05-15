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
// 購入履歴＆明細をデータベースから取得
if(is_admin($user) === false){
    // 一般ユーザーであればユーザーID紐付けで検索
// order_idを使って購入明細情報の取得
$purchase_details = get_purchase_details($db,$user['user_id'],$order_id);
// user_idを使って購入履歴を取得
$purchase_history = get_purchase_history($db,$user['user_id']);
} else {
    // 管理者なら全検索
// order_idを使って購入明細情報の取得
$purchase_details = get_purchase_details_admin($db,$order_id);
// 購入履歴を取得
$purchase_history = get_purchase_history_admin($db);
}

// 購入明細の特殊文字をHTMLエンティティにする
$purchase_details = entity_change($purchase_details);
// 上に表示する購入履歴の特殊文字をHTMLエンティティにする
$purchase_history = entity_change($purchase_history);

// トークンを取得する
get_csrf_token();
// htmlページを表示
include VIEW_PATH . 'purchase_details_view.php';