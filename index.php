<?php

session_start();
require 'Paymentez/paymentez.php';
require 'configApp.php';

$domain = $_SERVER['HTTP_HOST'];
$appName = explode('.', $domain)[0];

$paymentez = new Paymentez($PAYMENTEZ_CLIENT_APP_CODE, $PAYMENTEZ_CLIENT_APP_KEY, $MERCHANT_ID, '', true);

if (isset($_POST['add'])) {
    /********************************** ADD CARD *************************************/

    $cardnumber = str_replace(' ', '', trim($_POST['card-number']));
    $cardnumber = substr($cardnumber, 0, 6);

    $type_card = $paymentez->get('/v2/card_bin/'.$cardnumber);
    print_r($type_card);
    $year = '20'.$_POST['expiry-year'];
    $month = sprintf('%02s', $_POST['expiry-month']);

    $body = array(
        'session_id' => session_id(),
        'user' => array(
            'id' => 'uid1234',
            'email' => 'info@troyantec.com',
            'fiscal_number' => $_POST['card-number'],
        ),
        'card' => array(
            'number' => $_POST['card-number'],
            'holder_name' => $_POST['card-holder'],
            'expiry_month' => (int) $month,
            'expiry_year' => (int) $year,
            'cvc' => $_POST['cvc'],
            'type' => $type_card['body']['card_type'],
        ),
    );
    print_r($body);
    $crearToken = $paymentez->post('/v2/card/add', $body);

    echo '<script> console.log('.json_encode($crearToken).');</script>';

    /************************************************* ******************************/
}

/************************************************* DELETE CARD ******************/
    /*$del = array(
        'card' => array(
            'token' => '14433277854768206979',
        ),
        'user' => array(
            'id' => 'uid1234',
        ),
    );

    $delete_card = $paymentez->post('/v2/card/delete/', $del);

    echo '<script> console.log('.json_encode($delete_card).');</script>';*/

/************************************************* ******************************/

/********************************************** BIN CARD ************************/
    /*$card = $paymentez->get('/v2/card_bin/411111');

    echo '<script> console.log('.json_encode($card).');</script>';*/

/************************************************* ******************************/

/************************************************* GET CARDS ********************/
    /*$get_cards = $paymentez->get('/v2/card/list');

    echo '<script> console.log('.json_encode($get_cards).');</script>';*/

/******************************************* ***********************************/

?>

<!DOCTYPE html>
<html>
<head>  
  <title>SDK PHP | Paymentez</title>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Adrian Lucero T.">
  <meta name="author" content="Troyan Technology">
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

  <link href="https://cdn.paymentez.com/js/1.0.1/paymentez.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.paymentez.com/js/1.0.1/paymentez.min.js"></script>

  <style>
    .panel {
      margin: 0 auto;
      background-color: #F5F5F7;
      border: 1px solid #ddd;
      padding: 20px;
      display: block;
      width: 80%;
      border-radius: 6px;
      box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }
    .btn {
      background: rgb(140,197,65); /* Old browsers */
      background: -moz-linear-gradient(top, rgba(140,197,65,1) 0%, rgba(20,167,81,1) 100%); /* FF3.6-15 */
      background: -webkit-linear-gradient(top, rgba(140,197,65,1) 0%,rgba(20,167,81,1) 100%); /* Chrome10-25,Safari5.1-6 */
      background: linear-gradient(to bottom, rgba(140,197,65,1) 0%,rgba(20,167,81,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#44afe7', endColorstr='#3198df',GradientType=0 );
      color: #fff;
      display: block;
      width: 100%;
      border: 1px solid rgba(46, 86, 153, 0.0980392);
      border-bottom-color: rgba(46, 86, 153, 0.4);
      border-top: 0;
      border-radius: 4px;
      font-size: 17px;
      text-shadow: rgba(46, 86, 153, 0.298039) 0px -1px 0px;
      line-height: 34px;
      -webkit-font-smoothing: antialiased;
      font-weight: bold;
      display : block;
      margin-top: 20px;
    }

    .btn:hover {
      cursor: pointer;
    }
  </style>

</head>
<body>
  <div class="panel">
    <form method="post" id="add-card-form">
    <div class="paymentez-form">
        <input class="card-number my-custom-class" name="card-number" placeholder="Card number">
        <input class="name" id="the-card-name-id" placeholder="Card Holders Name">
        <input class="expiry-month" name="expiry-month">
        <input class="expiry-year" name="expiry-year">
        <input class="cvc" name="cvc">
    </div>
    <button class="btn" name="add">Save</button>
    <br/>
    <div id="messages"></div>
    </form>
  </div>
<script>
$(function() {
    Paymentez.init('stg', '<?php echo $PAYMENTEZ_CLIENT_APP_CODE; ?>', '<?php echo $PAYMENTEZ_CLIENT_APP_KEY; ?>');
});
</script>
</body>
</html>