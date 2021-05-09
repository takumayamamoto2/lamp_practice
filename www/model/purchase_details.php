<?php
// 購入履歴表示関係で使う関数
require_once MODEL_PATH . 'db.php';

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