<?php namespace Worldpay;

class ResponseTest extends TestCase {

  public function testGettingResponseParameters()
  {
    $wp = new Worldpay;
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
  }

}


/*
object(Worldpay\Response)#221 (2) {
  ["config":protected]=>
  NULL
  ["parameters":protected]=>
  object(Symfony\Component\HttpFoundation\ParameterBag)#220 (1) {
    ["parameters":protected]=>
    array(25) {
      ["testMode"]=>
      string(3) "100"
      ["callbackPW"]=>
      string(12) "password_123"
      ["transStatus"]=>
      string(1) "Y"
      ["transId"]=>
      string(9) "123456789"
      ["instId"]=>
      string(6) "123456"
      ["cartId"]=>
      string(7) "my_shop"
      ["amount"]=>
      string(4) "9.99"
      ["cardType"]=>
      string(10) "MasterCard"
      ["currency"]=>
      string(3) "GBP"
      ["ipAddress"]=>
      string(11) "123.456.789"
      ["desc"]=>
      string(18) "The greatest thing"
      ["compName"]=>
      string(12) "Acme PHP Ltd"
      ["futurePayId"]=>
      NULL
      ["name"]=>
      string(12) "Philip Brown"
      ["address1"]=>
      string(13) "101 Blah Lane"
      ["address2"]=>
      string(0) ""
      ["address3"]=>
      string(0) ""
      ["town"]=>
      string(6) "London"
      ["postcode"]=>
      string(7) "E20 123"
      ["tel"]=>
      string(9) "123456789"
      ["fax"]=>
      string(0) ""
      ["country"]=>
      string(2) "GB"
      ["countryString"]=>
      string(14) "United Kingdom"
      ["email"]=>
      string(16) "phil@ipbrown.com"
      ["timestamp"]=>
      object(Carbon\Carbon)#219 (3) {
        ["date"]=>
        string(19) "2013-10-13 16:08:06"
        ["timezone_type"]=>
        int(3)
        ["timezone"]=>
        string(13) "Europe/London"
      }
    }
  }
}

*/
