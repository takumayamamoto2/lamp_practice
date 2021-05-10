<?php
// 購入履歴関係で使う関数
require_once MODEL_PATH . 'db.php';

/*ユーザー用検索*/

// 購入履歴を取得　一般ユーザーであればユーザーID紐付けで検索
function get_purchase_history($db,$user_id){
    $sql="
      SELECT 
        purchase.order_id AS order_id,
        purchase.createdate AS createdate,
        SUM(order_details.price * order_details.quantity) AS price
      FROM
        purchase
      INNER JOIN 
        order_details
      ON 
        purchase.order_id = order_details.order_id
      WHERE
        user_id = ?
      GROUP BY
        order_details.order_id
    ";
    return fetch_all_query($db, $sql, array($user_id));
}

// 購入明細情報を取得
function get_purchase_details($db,$user_id,$order_id){
  $sql="
    SELECT
      purchase.order_id AS order_id,
      order_details.item_name AS name,
      order_details.price AS price,
      order_details.quantity AS quantity,
      order_details.price * order_details.quantity AS price_sum
    FROM
      purchase
    INNER JOIN
      order_details
    ON
      purchase.order_id = order_details.order_id
    WHERE
      purchase.user_id = ? AND
      purchase.order_id = ?
    ";
    return fetch_all_query($db, $sql, array($user_id,$order_id));
}

// 購入履歴を取得(注文番号紐付け)
function get_purchase_detail($db,$user_id,$order_id){
  $sql="
    SELECT 
      purchase.order_id AS order_id,
      purchase.createdate AS createdate,
      SUM(order_details.price * order_details.quantity) AS price
    FROM
      purchase
    INNER JOIN 
      order_details
    ON 
      purchase.order_id = order_details.order_id
    WHERE
      user_id = ? AND
      order_details.order_id = ?
    GROUP BY
      order_details.order_id
  ";
  return fetch_query($db, $sql, array($user_id,$order_id));
}


/*管理者用検索*/

// 購入履歴を取得 管理者なら全検索
function get_purchase_history_admin($db){
  $sql="
    SELECT 
      purchase.order_id AS order_id,
      purchase.createdate AS createdate,
      SUM(order_details.price * order_details.quantity) AS price
    FROM
      purchase
    INNER JOIN 
      order_details
    ON 
      purchase.order_id = order_details.order_id
    GROUP BY
      order_details.order_id
  ";
  return fetch_all_query($db, $sql);
}
// 購入明細情報を取得 管理者ならユーザーID指定なし
function get_purchase_details_admin($db,$order_id){
  $sql="
    SELECT
      purchase.order_id AS order_id,
      order_details.item_name AS name,
      order_details.price AS price,
      order_details.quantity AS quantity,
      order_details.price * order_details.quantity AS price_sum
    FROM
      purchase
    INNER JOIN
      order_details
    ON
      purchase.order_id = order_details.order_id
    WHERE
      purchase.order_id = ?
    ";
    return fetch_all_query($db, $sql, array($order_id));
}


// 購入履歴を取得(注文番号紐付け) 管理者ならユーザーID指定なし
function get_purchase_detail_admin($db,$order_id){
  $sql="
    SELECT 
      purchase.order_id AS order_id,
      purchase.createdate AS createdate,
      SUM(order_details.price * order_details.quantity) AS price
    FROM
      purchase
    INNER JOIN 
      order_details
    ON 
      purchase.order_id = order_details.order_id
    WHERE
      order_details.order_id = ?
    GROUP BY
      order_details.order_id
  ";
  return fetch_query($db, $sql, array($order_id));
}

// ランキング表示に使う関数
function get_items_ranking($db){
  $sql="
    SELECT 
      order_details.item_id,
      items.name,
      items.price,
      items.image,
      items.status,
      SUM(order_details.quantity) AS quantity_sum
    FROM
      order_details
    INNER JOIN
      items
    ON
      order_details.item_id = items.item_id
    GROUP BY
      item_id
    ORDER BY
      quantity_sum DESC
    LIMIT 3
  ";
  return fetch_all_query($db,$sql);
}