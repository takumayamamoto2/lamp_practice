<?php
/* 購入完了ページ */

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

// セッション開始
session_start();
// ログイン状態が確認できない場合はログイン画面に飛ぶ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// トークンがPOSTの値とセッションの値で同一であるか調べ、
// 検証後、セッションを破棄し、違っていればメッセージセット＆引数のページに飛ぶ
is_valid_csrf_token_check(CART_URL);

$db = get_db_connect();
$user = get_login_user($db);

$carts = get_user_carts($db, $user['user_id']);
// 特殊文字をHTMLエンティティにする
$carts = entity_change($carts);

if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

$total_price = sum_carts($carts);
// トークンを取得する
get_csrf_token();
include_once '../view/finish_view.php';
