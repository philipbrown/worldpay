<?php
// Require autoload
require __DIR__.'/vendor/autoload.php';

// Create new Worldpay object
$wp = new Philipbrown\Worldpay\Worldpay;

// Set the environment
$wp->setConfig(array('env' => 'development'));

// Create a new Request
$request = $wp->request(array(
  'instId' => '123456789',
  'cartId' => 'worldpay-php',
  'currency' => 'GBP',
  'environment' => 'development',
  'name' => 'Philip Brown',
  'email' => 'phil@ipbrown.com',
  'address_line_1' => '101 Blah Blah Lane',
  'town' => 'London',
  'postcode' => 'E20 123',
  'country' => 'GB',
  'telephone' => '123456789',
  'payment_type' => 'VISA',
  'amount' => '99.99'
));

// Set your secret
$request->setSecret('my_secret');

// Set custom signature fields
$request->setSignatureFields(array('email'));
