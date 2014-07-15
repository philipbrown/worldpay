<?php

use PhilipBrown\WorldPay\Environment;

class EnvironmentTest extends PHPUnit_Framework_TestCase {

  public function testCreateProductionEnvironment()
  {
    $env = new Environment('production');
    $this->assertEquals('production', $env->asString());
    $this->assertEquals(0, $env->asInt());
  }

  public function testCreateDevelopmentEnvironment()
  {
    $env = new Environment('development');
    $this->assertEquals('development', $env->asString());
    $this->assertEquals(100, $env->asInt());
  }

  public function testCreateCustomEnvironment()
  {
    $env = new Environment('local');
    $this->assertEquals('local', $env->asString());
    $this->assertEquals(100, $env->asInt());
  }

}
