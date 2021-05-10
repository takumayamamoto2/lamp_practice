<?php
/* 商品一覧ページ */

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
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
// 公開ステータスの商品を取得
$items = get_open_items($db);
// 特殊文字をHTMLエンティティにする
$items = entity_change($items);
// ランキングの商品情報を取得
$rankings = get_items_ranking($db);
// 特殊文字をHTMLエンティティにする
$rankings = entity_change($rankings);

// トークンを取得する
get_csrf_token();
// パスを指定して商品一覧ページのファイルを読み込む
include_once VIEW_PATH . 'index_view.php';
