<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();
// 既にログイン状態であれば
if(is_logined() === true){
  // 商品一覧ページへ飛ぶ
  redirect_to(HOME_URL);
}
// ログインページで入力したユーザー名を取得する
$name = get_post('name');
// ログインページで入力したパスワードを取得する
$password = get_post('password');
// データベースへの接続を確立する
$db = get_db_connect();

// ログインページで入力した情報を、データベースから探す
$user = login_as($db, $name, $password);
// データベースに一致するユーザー情報が無ければ、ログインに失敗を表示
if( $user === false){
  set_error('ログインに失敗しました。');
  // ログインページへ飛ぶ
  redirect_to(LOGIN_URL);
}

// 管理者番号だったら管理者画面へ飛ぶ
set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}

// 商品一覧ページへ飛ぶ
redirect_to(HOME_URL);