<?php namespace Philipbrown\Worldpay\Test;

use Philipbrown\Worldpay\Worldpay;

class ResponseTest extends TestCase {

  public function testGettingResponseParameters()
  {
    $wp = $this->getWorldPay();
    $r = $wp->response($this->getNormalResponse());
    $this->assertEquals('123456', $r->instId);
    $this->assertEquals('my_shop', $r->cartId);
    $this->assertEquals('9.99', $r->amount);
    $this->assertEquals('GBP', $r->currency);
    $this->assertEquals('The greatest thing', $r->description);
    $this->assertEquals('Philip Brown', $r->name);
    $this->assertEquals('London', $r->town);
    $this->assertEquals('E20 123', $r->postcode);
    $this->assertEquals('GB', $r->country);
    $this->assertEquals('phil@ipbrown.com', $r->email);
    $this->assertEquals('development', $r->environment);
    $this->assertEquals('password_123', $r->password);
    $this->assertEquals('123456789', $r->transaction_id);
    $this->assertEquals('MasterCard', $r->card_type);
    $this->assertEquals('123.456.789', $r->ip_address);
    $this->assertEquals('Acme PHP Ltd', $r->company_name);
    $this->assertEquals('101 Blah Lane', $r->address_line_1);
    $this->assertEquals('123456789', $r->telephone);
    $this->assertEquals('', $r->fax);
    $this->assertEquals('United Kingdom', $r->country_string);
    $this->assertInstanceOf('Carbon\Carbon', $r->timestamp);
    $this->assertEquals($this->getToday(), $r->timestamp);
  }

  public function testResponseHelperMethods()
  {
    $wp = $this->getWorldPay();
    $r = $wp->response($this->getNormalResponse());
    $this->assertEquals(true, $r->isValid('password_123'));
    $this->assertEquals(true, $r->isSuccess());
    $this->assertEquals(false, $r->isCancelled());
    $this->assertEquals(false, $r->isFuturePay());
    $this->assertEquals(false, $r->isProduction());
    $this->assertEquals(true, $r->isDevelopment());
  }

  public function testFuturePayResponse()
  {
    $wp = $this->getWorldPay();
    $r = $wp->response($this->getFuturePayResponse());
    $this->assertEquals('987654321', $r->futurepay_id);
    $this->assertEquals(true, $r->isFuturePay());
  }

  public function testFakeRequestResponse()
  {
    $wp = $this->getWorldPay(array(
      'env' => 'local',
      'password' => 'password_123'
    ));
    $r = $wp->response($this->getNormalRequest());
    $this->assertEquals('123456789', $r->instId);
    $this->assertEquals('my_shop', $r->cartId);
    $this->assertEquals('99.99', $r->amount);
    $this->assertEquals('GBP', $r->currency);
    $this->assertEquals('Philip Brown', $r->name);
    $this->assertEquals('101 Blah Blah Lane', $r->address_line_1);
    $this->assertEquals('London', $r->town);
    $this->assertEquals('GB', $r->country);
    $this->assertEquals('123456789', $r->telephone);
    $this->assertEquals('VISA', $r->card_type);
    $this->assertEquals(true, $r->isValid('password_123'));
    $this->assertEquals(true, $r->isSuccess());
    $this->assertEquals(false, $r->isCancelled());
    $this->assertEquals(false, $r->isFuturePay());
    $this->assertEquals(false, $r->isProduction());
    $this->assertEquals(true, $r->isDevelopment());
  }

}
