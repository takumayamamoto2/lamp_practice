<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// ユーザーIDを紐付けてカートテーブルの内容を取得
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  return fetch_all_query($db, $sql, array($user_id));
}

// ログインユーザーのカート情報から該当商品を取得(押したボタンの商品)
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  return fetch_query($db, $sql, array($user_id,$item_id));

}

// カートの追加 クリックした商品IDが送られてきたらカートの追加処理を行う
function add_cart($db, $user_id, $item_id ) {
  // カート内の商品情報を取ってくる
  $cart = get_user_cart($db, $user_id, $item_id);
  // カート内にクリックした商品が無かったらINSERTで追加
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  // カート内にクリックした商品と同一商品があれば、クリックした商品amount(数量)に+1
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, ?)
  ";

  return execute_query($db, $sql, array($item_id,$user_id,$amount));
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  return execute_query($db, $sql,array($amount,$cart_id));
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, array($cart_id));
}
// カートテーブルの情報を入れると購入処理を行う
function purchase_carts($db, $carts){
  // カートテーブルの情報を入れるとカートの中身の検証を行う
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  // カート情報を入れて、ループで順番にpurchase、order_detailsテーブルに購入情報を書き込む
  foreach($carts as $cart){
    order_details_transaction($db,$cart['user_id'],$cart['item_id'],$cart['name'],$cart['price'],$cart['amount']);
  }

  delete_user_carts($db, $carts[0]['user_id']);
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  execute_query($db, $sql,array($user_id));
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

// カートテーブルの商品が購入できるか検証する
function validate_cart_purchase($carts){
  // カートテーブル内の商品があるかどうかチェック
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  // 二次元配列から値を取り出す
  foreach($carts as $cart){
    // 公開ステータスが1では無かったら"購入できません"
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // 在庫数 - カート数量が0以下なら"在庫が足りません"
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}


// 購入時の商品データテーブル更新文
function insert_order_details($db,$item_id,$item_name,$price,$quantity){
  $sql = "
  INSERT INTO 
  order_details(item_id,item_name,price,quantity)
  VALUES (?, ?, ?, ?)
  ";

  return execute_query($db,$sql,array($item_id,$item_name,$price,$quantity));
}

// 購入履歴テーブル更新文
function insert_purchase($db,$user_id){
  $sql = "
  INSERT INTO 
  purchase(order_id,user_id)
  VALUES (?, ?)
  ";

  // 直前のINSERTで使用した、オートインクリメントの値を取得
  $order_id = $db -> lastInsertId();
  return execute_query($db,$sql,array($order_id,$user_id));
}

// カート情報をトランザクションでpurchase、order_detailsテーブルに購入情報を書き込む
function order_details_transaction($db,$user_id,$item_id,$item_name,$price,$quantity){
  $db->beginTransaction();
  if(insert_order_details($db,$item_id,$item_name,$price,$quantity) 
    && insert_purchase($db,$user_id)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}