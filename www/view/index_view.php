<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print($item['name']); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print(number_format($item['price'])); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                    <input type="hidden" name="csrf_token" value="<?php print $_SESSION['csrf_token']; ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      
        <h1>人気ランキング</h1>
        <div class="row">
          <?php foreach($rankings as $ranking){ ?>
          <div class="col-12 item">
            <div class="card h-100 text-center">
            <?php $rank++; ?>
            <h2><?php print $rank ; ?>位</h2>
              <div class="card-header">
                <?php print $ranking['name'] ; ?>
              </div>
              <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . $ranking['image']); ?>">
              <figcaption>
              </figcaption>
              </div>
            </div>
          <?php } ?>
          </div>
        </div>
    </div>
  </div>
</body>
</html>