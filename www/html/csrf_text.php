<?php
// トークンの生成…ランダムな文字列を生成し、セッションに保存
function get_csrf_token(){
  // get_random_string()はユーザー定義関数。30文字のランダムな文字列を生成
  $token = get_random_string(30);
  // set_session()はユーザー定義関数。'csrf_token'という名で、セッションにランダムな文字列をセット
  set_session('csrf_token', $token);
  return $token;
}

// トークンのチェック…ユーザーがフォームデータを送った時、POSTの中身のトークンを確認
function is_valid_csrf_token($token){
  // POSTで送られてきたトークンが入っていなかったらfalseを返す
  if($token === '') {
    return false;
  }
  // get_session()はユーザー定義関数
  // ユーザーが送ったフォームデータのトークンと生成時のトークンが同じか確認
  return $token === get_session('csrf_token');
}



/*使う関数メモ*/

// 引数に数が入るとその数分だけランダムな文字列を返す　初期値は20
function get_random_string($length = 20){
    // substr(対象の文字列,取り出し開始位置,取り出す文字のバイト数)　返り値…取り出した文字列
    // base_convert(変換する数値,変換前の基数(ここでは16進数),変換後の基数(ここでは36進数(アルファベットに合わせている)))
    // hash(ハッシュ関数の変換方式の種類,変換する値) uniqid()…マイクロ秒を元に唯一な値を生成
    return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
  }

// set_session関数　'csrf_token'という名で、セッションにランダムな文字列をセット
function set_session($name, $value){
    $_SESSION[$name] = $value;
  }

// get_session関数
function get_session($name){
    // 'csrf_token'という名のランダムな文字列が$_SESSIONに入っているかどうかを確認
    if(isset($_SESSION[$name]) === true){
      // 'csrf_token'のセッションを返す
      return $_SESSION[$name];
    };
    // $_SESSIONの中身が無かったら空文字を返す
    return '';
  }


/* 実装コードの流れ
1.ログイン直後、get_csrf_token関数でランダムな文字列(トークン)を取得し、セッションに保存。

2.全てのフォームタグの使用場所にhiddenで$_SESSION['csrf_token']（トークン）をセット。

3.フォームで送信をしたとき、is_valid_csrf_token関数で
  セッションに保存したトークンとPOSTで送信されたトークンが同じであるか検証。
  トークンが違っていればフォーム操作を無効にし、エラーメッセージを出す。

4.トークンが同じだった場合、フォーム処理の直後に現在のトークンを破棄し、
  get_csrf_token関数で新たなトークンを取得し、セッションへ保存。

・トークン破棄
  unset関数でトークンの入ったセッションを指定
  unset($_SESSION['csrf_token'])
*/