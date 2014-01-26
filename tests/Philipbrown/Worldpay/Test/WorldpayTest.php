<?php namespace Philipbrown\WorldPay\Test;

use PhilipBrown\WorldPay\WorldPay;

class WorldPayTest extends TestCase {

  public function testRequestInstantiation()
  {
    $this->assertInstanceOf('PhilipBrown\WorldPay\Request', $this->getWorldPay()->request($this->getNormalRequest()));
  }

  public function testResponseInstantiation()
  {
    $this->assertInstanceOf('PhilipBrown\WorldPay\Response', $this->getWorldPay()->response($this->getNormalResponse()));
  }

  public function testCallbackURL()
  {
    $wp = $this->getWorldPay();
    $request = $wp->request(array(
      'instId' => '123456',
      'cartId' => 'my_shop',
      'currency' => 'GBP',
      'amount' => 9.99
    ));
    $prepare = $request->prepare();
    $this->assertEquals('https://secure-test.worldpay.com/wcc/purchase', $prepare['endpoint']);
  }

  public function testEnvironementCallbackURL()
  {
    $wp = $this->getWorldPay(array(
      'env' => 'local',
      'url' => 'example.local/callbacks/worldpay'
    ));
    $request = $wp->request(array(
      'instId' => '123456',
      'cartId' => 'my_shop',
      'currency' => 'GBP',
      'amount' => 9.99
    ));
    $prepare = $request->prepare();
    $this->assertEquals('example.local/callbacks/worldpay', $prepare['endpoint']);
  }

}
