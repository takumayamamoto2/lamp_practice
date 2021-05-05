-- 課題2－1 新規追加テーブル


-- 購入履歴テーブル 
CREATE TABLE purchase (
  order_id int(11) NOT NULL AUTO_INCREMENT comment '注文番号',
  user_id int(11) NOT NULL comment 'ユーザーID',
  createdate datetime NOT NULL comment '購入日時',
  primary key(order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 購入時の商品データテーブル 
CREATE TABLE order_details (
  order_id int(11) NOT NULL AUTO_INCREMENT comment '注文番号',
  item_id int(11) NOT NULL comment '商品のID',
  item_name varchar(100) NOT NULL comment '商品の名前',
  price int(11) NOT NULL comment '購入時商品の価格',
  quantity int(11) NOT NULL comment '購入数',
  primary key(order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- メモ

-- ENGINE=InnoDB DEFAULT CHARSET=utf8
-- MYSQLのDBエンジンをInnoDBにして、初期設定の文字コードをUTF-8にして処理
-- トランザクションが使える

-- NOT NULL … NULLの値を入れないようにする