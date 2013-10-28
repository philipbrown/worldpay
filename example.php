<?php
// Require autoload
require __DIR__.'/vendor/autoload.php';

// Create new Worldpay object
$wp = new Philipbrown\Worldpay\Worldpay;

$fake = array(
      'instId' => '123456789',
      'cartId' => 'my_shop',
      'currency' => 'GBP',
      'name' => 'Philip Brown',
      'email' => 'phil@ipbrown.com',
      'address_line_1' => '101 Blah Blah Lane',
      'town' => 'London',
      'postcode' => 'E20 123',
      'country' => 'GB',
      'telephone' => '123456789',
      'payment_type' => 'VISA',
      'amount' => '99.99',
      'CM_order_id' => 456,
      'MC_customer_id' => 123
    );

$wp->setConfig(array(
  'env' => 'staging',
  'password' => 'password_123'
));

// Create a new Request
$response = $wp->response($fake);

var_dump($response);

var_dump($response->isSuccess());
var_dump($response->isDevelopment());
