<?php

function dd($var){
  var_dump($var);
  exit();
}
// 指定のページへ飛ばす
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

// 引数に名前が入ると名前に応じたPOSTデータを返す
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

// セッションに保存されている名前を入れたら$_SESSIONのセットを返す
function get_session($name){
  // 入れた名前の中身が$_SESSIONに入っているかどうかを確認
  if(isset($_SESSION[$name]) === true){
    // 入れた名前で保存されているセッションを返す
    return $_SESSION[$name];
  };
  // $_SESSIONの中身が無かったら空文字を返す
  return '';
}

// 名前と値を入れるとセッションに値を保存する
function set_session($name, $value){
  $_SESSION[$name] = $value;
}
// 引数にエラーメッセージを入れるとセッションにメッセージを保存できる
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

// セッションに保存されているエラーメッセージの取得
function get_errors(){
  // セッションに保存されているエラーメッセージを取得
  $errors = get_session('__errors');
  // エラーメッセージが無かったら空の配列を返す
  if($errors === ''){
    return array();
  }
  // セッションに空の配列を保存（次回以降のエラーメッセージの初期化）
  set_session('__errors',  array());
  // エラーメッセージを返す
  return $errors;
}

function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

function set_message($message){
  $_SESSION['__messages'][] = $message;
}

function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}
// ログイン状態を確認
function is_logined(){
  return get_session('user_id') !== '';
}

function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

function delete_image($filename){
  // file_exists…ファイルが存在するかどうかを調べる
  if(file_exists(IMAGE_DIR . $filename) === true){
    // unlink…名前を削除し、場合によってはそれが参照しているファイルも削除する
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}



function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}


function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

// 特殊文字をHTMLエンティティに変換
function entity_str($str){
  return  htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

// 一次元配列の特殊文字をHTMLエンティティに変換
// 一次元配列の値を取り出して変換する
function entity_change_one($one_array) {
  // 一次元配列の値を取り出す
  foreach ($one_array as $key => $value) {
      // 特殊文字をHTMLエンティティに変換
      $one_array[$value] = entity_str($value);
  }
  return $one_array;
}

// 二次元配列の特殊文字をHTMLエンティティに変換
// 一次元配列の値のみを取り出して変換する)
function entity_change($two_array) {
  // 二次元配列を一次元配列にする
  foreach ($two_array as $key => $value) {
    // 一次元配列の値のみを取り出す	  
    foreach ($value as $keys => $values) {
      // 特殊文字をHTMLエンティティに変換
      $two_array[$key][$keys] = entity_str($values);
    }
  }

  return $two_array;
}

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

// トークンがPOSTの値とセッションの値で同一であるか調べ、
// 検証後、セッションを破棄し、違っていれば引数のページに飛ぶ
function is_valid_csrf_token_check($redirect){
  // ポストから受信したトークンを取得
  $token = get_post('csrf_token');
  // POSTのトークンとセッションに保存したトークンが同一であるか検証
  if(is_valid_csrf_token($token) === false){
    // 現在のセッションに保存されているトークンを破棄
    unset($_SESSION['csrf_token']);
    set_error('不正な処理が行われています');
    // トークンが違っていたらその後の処理を行わずにカートページへ飛ぶ
    redirect_to($redirect);
  } else{
  // 現在のセッションに保存されているトークンを破棄
  unset($_SESSION['csrf_token']);
  }
}