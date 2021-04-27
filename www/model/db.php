<?php

function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  return $dbh;
}

// sqlを実行して一行のみ取得
function fetch_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetch();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

// sqlを実行して全行取得
function fetch_all_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

// sqlを実行（取得なし）
function execute_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。'.$e);
  }
  return false;
}

/*
// sqlを実行 (取得なし）
function execute_query($db, $sql, $cart_id=false,$amount=false,$stock=false,$item_id=false,$status=false){
  try{
    $statement = $db->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    if($amount !== false){$statement->bindValue(':amount',$amount, PDO::PARAM_INT);}
    if($cart_id !== false){$statement->bindValue(':cart_id',$cart_id, PDO::PARAM_INT);}
    if($stock !== false){$statement->bindValue(':stock',$stock, PDO::PARAM_INT);}
    if($item_id !== false){$statement->bindValue(':item_id',$item_id, PDO::PARAM_INT);}
    if($status !== false){$statement->bindValue(':status',$status, PDO::PARAM_INT);}
    if($name !== false){$statement->bindValue(':name',$stock, PDO::PARAM_STR);}
    if($price !== false){$statement->bindValue(':price',$item_id, PDO::PARAM_INT);}
    if($filename !== false){$statement->bindValue(':filename',$filename, PDO::PARAM_STR);}
    if($f !== false){$statement->bindValue(':filename',$filename, PDO::PARAM_STR);}


    return $statement->execute();
  }catch(PDOException $e){
    set_error('更新に失敗しました。'.$e);
  }
  return false;
}
*/



