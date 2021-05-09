<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>購入履歴画面</title>
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
    <?php include VIEW_PATH . 'templates/header_logined.php'; ?>

    <div class="container">
        <h1>購入履歴</h1>
        <?php include VIEW_PATH . 'templates/messages.php'; ?>
        <?php if(count($purchase_history) > 0){ ?>
          <table class="table table-bordered text-center">
              <thead class="thead-light">
                <tr>
                  <th>注文番号</th>
                  <th>購入日時</th>
                  <th>該当の注文の合計金額</th>
                  <th>購入明細表示</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($purchase_history as $value){ ?>
                <tr>  
                  <td><?php print $value['order_id'];?></td>
                  <td><?php print $value['createdate'];?></td>
                  <td><?php print number_format($value['price']).'円';?></td>
                  <td>
                    <form action="purchase_details.php" method="post">
                      <input type="submit" value="購入明細表示" class="btn btn-primary">
                      <input type="hidden" name="order_id" value="<?php print $value['order_id']; ?>">
                      <input type="hidden" name="csrf_token" value="<?php print $_SESSION['csrf_token']; ?>">
                    </form>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
          </table>
        <?php } else { print '購入履歴はありません';} ?>
    </div>
</body>
</html>