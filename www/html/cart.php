<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
// ログイン状態が確認できない場合はログイン画面に飛ぶ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースの接続を確立する
$db = get_db_connect();
// ログインしているユーザーデータを返す
$user = get_login_user($db);
// ログインユーザーのカート情報を取得
$carts = get_user_carts($db, $user['user_id']);
// 特殊文字をHTMLエンティティにする
$carts = entity_change($carts);

$total_price = sum_carts($carts);
// トークンを取得する
get_csrf_token();
// htmlページを表示
include_once VIEW_PATH . 'cart_view.php';
