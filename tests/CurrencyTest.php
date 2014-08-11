<?php

use PhilipBrown\WorldPay\Currency;

class CurrencyTest extends PHPUnit_Framework_TestCase {

  /** @test */
  public function should_set_new_currency()
  {
    $currency = Currency::set('GB');

    $this->assertInstanceOf('PhilipBrown\WorldPay\Currency', $currency);
    $this->assertEquals('GB', (string) $currency);
  }

  /** @test */
  public function should_only_accept_valid_worldpay_currencies()
  {
    $this->setExpectedException('Assert\AssertionFailedException');

    $currency = Currency::set('BTC');
  }

}
