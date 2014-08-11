<?php

use PhilipBrown\WorldPay\Callback;

class CallbackTest extends PHPUnit_Framework_TestCase {

  /** @test */
  public function should_create_a_new_callback()
  {
    $callback = Callback::set('http://shop.com/callbacks/worldpay');

    $this->assertInstanceOf('PhilipBrown\WorldPay\Callback', $callback);
    $this->assertEquals('http://shop.com/callbacks/worldpay', (string) $callback);
  }

  /** @test */
  public function should_only_accept_valid_urls()
  {
    $this->setExpectedException('Assert\AssertionFailedException');

    $callback = Callback::set('...');
  }

}
