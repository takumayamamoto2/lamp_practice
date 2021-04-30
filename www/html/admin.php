<?php
/* 商品管理ページ */

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

// ログイン状態が確認できない場合はログイン画面に飛ぶ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースの接続を確立する
$db = get_db_connect();
// ログインしているユーザーデータを返す
$user = get_login_user($db);

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$items = get_all_items($db);

// トークンを取得する
get_csrf_token();
// 特殊文字をHTMLエンティティにする
$items = entity_change($items);
include_once VIEW_PATH . '/admin_view.php';
