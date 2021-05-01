<?php
/* 「カートに追加」ボタンをクリックしたときの処理 */

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
is_valid_csrf_token_check(HOME_URL);

// データベースの接続を確立する
$db = get_db_connect();
// ログインしているユーザーデータを返す
$user = get_login_user($db);

// POSTで受信したアイテムIDを取得
$item_id = get_post('item_id');

// add_cart関数でカートの追加処理を行う
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

redirect_to(HOME_URL);