<h1 align="center">
  <a href="http://developers.troyantechnology.com/es/">
    <img src="https://user-images.githubusercontent.com/1153516/29861072-689ec57e-8d3e-11e7-8368-dd923543258f.jpg" alt="Troyan Technology Developers" width="230"></a>
  </a>
  <br>
  Paymentez PHP SDK
  <br>
</h1>

<h4 align="center">This is the official PHP SDK for Paymentez Platform.</h4>

<p align="center">
  <a href="https://heroku.com/deploy?template=https://github.com/troyantec/paymentez-php-sdk">
    <img src="https://www.herokucdn.com/deploy/button.svg" alt="Deploy">
  </a>
</p>

<p align="center">
  <a href="https://heroku.com/deploy?template=https://github.com/troyantec/paymentez-php-sdk">
    <img src="https://user-images.githubusercontent.com/1153516/29859906-9453b50c-8d3a-11e7-88b6-ab354d4a4908.png">
  </a>
</p>


## How do I install it?

       clone repository
       https://github.com/troyantec/paymentez-php-sdk.git

## How do I use it?

The first thing to do is to instance a ```Paymentez``` class. You'll need to give a ```PAYMENTEZ_CLIENT_APP_CODE``` and a ```PAYMENTEZ_CLIENT_APP_KEY```. You can obtain both after creating your own application. For more information on this please read: [creating an application](http://developers.troyantechnology.com/application-manager/)

### Including the Lib
Include the lib paymentez in your project

```php
require '/Paymentez/paymentez.php';
```
Start the development!

### Create an instance of Paymentez class
Simple like this
```php
$paymentez = new Paymentez('1234', 'a secret');
```
With this instance you can start working on MercadoLibre's APIs.

There are some design considerations worth to mention.

1. This SDK is just a thin layer on top of an http client to handle all the OAuth WebServer flow for you.

2. There is JSON parsing. this SDK will include [json](http://php.net/manual/en/book.json.php) for internal usage.

3. This SDK will include [curl](http://php.net/manual/en/book.curl.php) for internal usage.

4. If you already have the access_token and the refresh_token you can pass in the constructor

```php
$paymentez = new Paymentez('1234', 'a secret', true, 'MERCHANT_ID');
```

Thanks for helping!
