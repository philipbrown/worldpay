<?php

use PhilipBrown\WorldPay\Config;

class ConfigTest extends PHPUnit_Framework_TestCase {

  /** @test */
  public function should_get_config_properties()
  {
    $config = new Config('123', '456', 'qwerty', 'testing', 'http://callback.com');
    $this->assertEquals('123', $config->instId);
    $this->assertEquals('456', $config->cartId);
    $this->assertEquals('qwerty', $config->password);
    $this->assertEquals('testing', $config->env);
    $this->assertEquals('http://callback.com', $config->callback);
  }

}
