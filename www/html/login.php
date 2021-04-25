<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// functions(関数)ファイル読み込み
require_once MODEL_PATH . 'functions.php';
session_start();

// ログインしていたら商品一覧画面へ
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// ログイン画面を読み込む
include_once VIEW_PATH . 'login_view.php';

/*
require_once … ファイル読み込みに失敗するとエラーが発生して処理が停止する
処理系統に使う

include_once … ファイル読み込みに失敗しても警告はあるが処理は停止しない
処理が停止すると画面に何も映らなくなってしまうのでviewに使う

*/