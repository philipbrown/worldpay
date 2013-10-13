<?php namespace Worldpay;

class WorldpayTest extends TestCase {

  public function testRequestInstantiation()
  {
    $this->assertInstanceOf('Worldpay\Request', $this->getWorldpay()->request($this->getNormalRequest()));
  }

  public function testResponseInstantiation()
  {
    $this->assertInstanceOf('Worldpay\Response', $this->getWorldpay()->response($this->getNormalResponse()));
  }

}
