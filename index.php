<?php
const CALC_NUM_MIN = 1;
const CALC_NUM_MAX = 10;
const CALC_NUM_DEFALUT = 3;
$calc_num = isset($_GET['calc-num']) && is_numeric($_GET['calc-num']) ? $_GET['calc-num'] : CALC_NUM_DEFALUT;
$calc_num = $calc_num > CALC_NUM_MAX ? CALC_NUM_MAX : $calc_num;
$calc_num = $calc_num < CALC_NUM_MIN ? CALC_NUM_MIN : $calc_num;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="保有銘柄の評価額を均等にするための購入金額と購入株数を計算するツールです。">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
  <style>
    /* 第1,5,6カラムに適用 */
    .col156-align td:nth-of-type(1), td:nth-of-type(5), td:nth-of-type(6) {
      text-align: right;
    }
  </style>
  <title>株式買付金額計算ツール</title>
</head>
<body>
  <h1>株式買付金額計算ツール</h1>
  <form id="calc-form" action="index.php" method="GET">
    <div class="d-flex flex-row my-2">
      <div>
        <button class="btn btn-outline-danger text-nowrap mx-3" type="reset">リセット</button>
      </div>
      <div>
        <div class="input-group">
          <input class="form-control" name="calc-num" type="number" placeholder="行数を入力して下さい" aria-describedby="calc-num-btn">
          <button id="calc-num-btn" class="btn btn-outline-primary" type="submit">変更</button>
        </div>
      </div>
      <div class="mx-3">
        <input id="purchase-capacity" class="form-control" type="number" placeholder="買付余力を入力して下さい" aria-describedby="calc-btn">
      </div>
    </div><!-- d-flex -->
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle col156-align">
        <thead>
          <tr class="text-center text-nowrap">
            <th>No</th>
            <th>銘柄</th>
            <th>評価額</th>
            <th>株価</th>
            <th>購入金額</th>
            <th>購入株数</th>
          </tr>
        </thead>
        <tbody>
<?php for ($i = 1; $i <= $calc_num; $i++) { ?>
          <tr>
            <td><?= $i ?></td>
            <td>
              <!-- 銘柄 -->
              <input class="form-control" type="text">
            </td>
            <td>
              <!-- 評価額 -->
              <input id="valuation-<?= $i ?>" class="form-control" type="number">
            </td>
            <td>
              <!-- 株価 -->
              <input id="stock-price-<?= $i ?>" class="form-control" type="number">
            </td>
            <td>
              <!-- 購入金額 -->
              <output id="purchase-price-<?= $i ?>"></output>
            </td>
            <td>
              <!-- 購入株数 -->
              <output id="purchase_num-<?= $i ?>"></output>
            </td>
          </tr>
<?php } ?>
        </tbody>
      </table>
    </div><!-- table-responsive -->
  </form>
  <script>
    window.addEventListener('DOMContentLoaded', function() {
      document.getElementById('calc-form').addEventListener('input', function(e) {
        let total_amount = Number(document.getElementById('purchase-capacity').value);  // 資産総額を買付余力で初期化
<?php for ($i = 1; $i <= $calc_num; $i++) { ?>
        total_amount += Number(document.getElementById('valuation-<?= $i ?>').value);  // 資産総額に各銘柄の評価額を加算
<?php } ?>

        let target_valuation = total_amount / <?= $calc_num ?>;  // 目標評価額
        let valuation;       // 評価額
        let stock_price;     // 株価
        let purchase_price;  // 購入金額
        let purchase_num;    // 購入株数
<?php for ($i = 1; $i <= $calc_num; $i++) { ?>
        valuation = Number(document.getElementById('valuation-<?= $i ?>').value);
        stock_price = Number(document.getElementById('stock-price-<?= $i ?>').value);
        purchase_price = Math.round((target_valuation - valuation) * 100) / 100;  // 購入金額を計算
        purchase_num = Math.floor(purchase_price / stock_price);  // 購入株数を計算
        document.getElementById('purchase-price-<?= $i ?>').value = purchase_price;
        document.getElementById('purchase_num-<?= $i ?>').value = purchase_num;
<?php } ?>
      });
    });
  </script>
</body>
</html>
