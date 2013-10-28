<?php namespace Philipbrown\Worldpay\Test;

use Philipbrown\Worldpay\Worldpay;
use PHPUnit_Framework_TestCase;
use Carbon\Carbon;

class TestCase extends PHPUnit_Framework_TestCase {

  public function getWorldPay()
  {
    $wp = new Worldpay;
    $wp->setConfig(array('env' => 'development'));
    return $wp;
  }

  public function getToday()
  {
    return Carbon::now();
  }

  public function getTomorrow()
  {
    $date = new Carbon('tomorrow');
    return $date->toDateString();
  }

  public function getNormalRequest()
  {
    return array(
      'instId' => '123456789',
      'cartId' => 'my_shop',
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
      'amount' => '99.99',
      'CM_order_id' => 456,
      'MC_customer_id' => 123
    );
  }

  public function getNormalResponse()
  {
    return array(
      'testMode' => '100',
      'callbackPW' => 'password_123',
      'transStatus' => 'Y',
      'transId' => '123456789',
      'transTime' => '1380101246760',
      'instId' => '123456',
      'cartId' => 'my_shop',
      'amount' => '9.99',
      'cardType' => 'MasterCard',
      'currency' => 'GBP',
      'ipAddress' => '123.456.789',
      'desc' => 'The greatest thing',
      'compName' => 'Acme PHP Ltd',
      'name' => 'Philip Brown',
      'address1' => '101 Blah Lane',
      'address2' => '',
      'address3' => '',
      'town' => 'London',
      'postcode' => 'E20 123',
      'tel' => '123456789',
      'fax' => '',
      'country' => 'GB',
      'countryString' => 'United Kingdom',
      'email' => 'phil@ipbrown.com'
    );
  }

  public function getFuturePayRequest()
  {
    return array(
      'instId' => '123456789',
      'cartId' => 'my_shop',
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
      'amount' => '99.99',
      'futurePay_type' => 'regular',
      'option' => 0,
      'start_date' => 'tomorrow',
      'interval' => '1 year',
      'initial_amount' => '99.99',
      'normal_amount' => '19.99',
    );
  }

  public function getFuturePayResponse()
  {
    return array(
      'testMode' => '100',
      'callbackPW' => 'password_123',
      'transStatus' => 'Y',
      'transId' => '123456789',
      'transTime' => '1380101246760',
      'instId' => '123456',
      'cartId' => 'my_shop',
      'amount' => '9.99',
      'cardType' => 'MasterCard',
      'currency' => 'GBP',
      'ipAddress' => '123.456.789',
      'desc' => 'The greatest thing',
      'compName' => 'Acme PHP Ltd',
      'name' => 'Philip Brown',
      'address1' => '101 Blah Lane',
      'address2' => '',
      'address3' => '',
      'town' => 'London',
      'postcode' => 'E20 123',
      'tel' => '123456789',
      'fax' => '',
      'country' => 'GB',
      'countryString' => 'United Kingdom',
      'email' => 'phil@ipbrown.com',
      'futurePayId' => '987654321',
    );
  }

}
