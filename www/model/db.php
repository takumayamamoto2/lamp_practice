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
    set_error('更新に失敗しました。');
  }
  return false;
}


// sqlを実行 (カート数量変更）
function execute_query_cart_amount($db, $sql){
  try{
    $statement = $db->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $statement->bindValue(1,$amount, PDO::PARAM_INT);
    $statement->bindValue(2,$cart_id, PDO::PARAM_INT);
    return $statement->execute();
  }catch(PDOException $e){
    set_error('更新に失敗しました。');
  }
  return false;
}


// sqlを実行 (商品管理画面の数量変更）
function execute_query_item_stock($db, $sql){
  try{
    $statement = $db->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $statement->bindValue(1,$stock, PDO::PARAM_INT);
    $statement->bindValue(2,$item_id, PDO::PARAM_INT);
    return $statement->execute();
  }catch(PDOException $e){
    set_error('更新に失敗しました。');
  }
  return false;
}











