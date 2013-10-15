<?php namespace Philipbrown\Worldpay\Test;

use Philipbrown\Worldpay\Worldpay;

class WorldpayTest extends TestCase {

  public function testRequestInstantiation()
  {
    $this->assertInstanceOf('Philipbrown\Worldpay\Request', $this->getWorldpay()->request($this->getNormalRequest()));
  }

  public function testResponseInstantiation()
  {
    $this->assertInstanceOf('Philipbrown\Worldpay\Response', $this->getWorldpay()->response($this->getNormalResponse()));
  }

  public function testEnvironementCallbackURL()
  {
    $wp = new WorldPay;
    $wp->setConfig(array(
      'env' => 'local',
      'local' => 'example.local/callbacks/worldpay'
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
