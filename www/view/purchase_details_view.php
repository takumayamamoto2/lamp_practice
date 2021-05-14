<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>購入明細画面</title>
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
    <?php include VIEW_PATH . 'templates/header_logined.php'; ?>

    <div class="container">
        <h1>購入明細</h1>
        <?php include VIEW_PATH . 'templates/messages.php'; ?>
        <?php if(count($purchase_history) > 0){ ?>
          <?php foreach($purchase_history as $value){ ?>
            <?php if($value['order_id'] === $order_id){ ?>
            <table class="table table-bordered text-center">
                <thead class="thead-light">
                  <tr>
                    <th>注文番号</th>
                    <th>購入日時</th>
                    <th>該当の注文の合計金額</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>  
                    <td><?php print $value['order_id'];?></td>
                    <td><?php print $value['createdate'];?></td>
                    <td><?php print number_format($value['price']).'円';?></td>
                  </tr>
                </tbody>
            </table>
            <?php } ?>
          <?php } ?>
        <?php } else { print '購入履歴はありません';} ?>
        

        <?php if(count($purchase_details) > 0){ ?>
          <table class="table table-bordered text-center">
              <thead class="thead-light">
                <tr>
                  <th>商品名</th>
                  <th>購入時の商品価格</th>
                  <th>購入数</th>
                  <th>小計</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($purchase_details as $value){ ?>
                <tr>  
                  <td><?php print $value['name'];?></td>
                  <td><?php print number_format($value['price']).'円';?></td>
                  <td><?php print ($value['quantity']);?></td>
                  <td><?php print ($value['price_sum']).'円';?></td>
                </tr>
                <?php } ?>
              </tbody>
          </table>
        <?php } else { print '購入履歴はありません';} ?>
    </div>
</body>
</html>