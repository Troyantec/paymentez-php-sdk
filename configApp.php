<?php

/* Go to My Apps dashboard: https://developers.troyantechnology.com.ar/apps/home, and get the information you need in order to the following enviroment variables */

/* Your Application Id */
$PAYMENTEZ_CLIENT_APP_CODE = getenv('PAYMENTEZ_CLIENT_APP_CODE');

/* Your Secret Key */
$PAYMENTEZ_CLIENT_APP_KEY = getenv('PAYMENTEZ_CLIENT_APP_KEY');

/* The Merchant id */
$MERCHANT_ID = getenv('MERCHANT_ID');

//////////////////////////////////////////////////////////////////////////////////////////////////////
//If you don't use Heroku use the next config

// $PAYMENTEZ_CLIENT_APP_CODE = 'App_ID';

// $PAYMENTEZ_CLIENT_APP_KEY = 'Secret_Key';

// $MERCHANT_ID = 'merchant_Id';
