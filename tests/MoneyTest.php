<?php

use PhilipBrown\WorldPay\Money;

class MoneyTest extends PHPUnit_Framework_TestCase {

  /** @test */
  public function should_set_money_value()
  {
    $money = Money::set(1000);

    $this->assertInstanceOf('PhilipBrown\WorldPay\Money', $money);
    $this->assertEquals('10.00', (string) $money);
  }

  /** @test */
  public function should_only_accept_integer_values()
  {
    $this->setExpectedException('Assert\AssertionFailedException');

    $money = Money::set('10.00');
  }

}
