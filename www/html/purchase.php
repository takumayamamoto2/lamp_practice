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
// データベースの接続を確立する
$db = get_db_connect();
// ログインしているユーザーデータを返す
$user = get_login_user($db);

// 購入履歴をデータベースから取得
if(is_admin($user) === false){
    // 一般ユーザーであればユーザーID紐付けで検索
    $purchase_history = get_purchase_history($db,$user['user_id']);
} else {
    // 管理者なら全検索
    $purchase_history = get_purchase_history_admin($db);
}

// 特殊文字をHTMLエンティティにする
$purchase_history = entity_change($purchase_history);
// トークンを取得する
get_csrf_token();
// htmlページを表示
include VIEW_PATH . 'purchase_view.php';