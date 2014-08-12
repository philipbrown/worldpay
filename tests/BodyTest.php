<?php

use PhilipBrown\WorldPay\Body;

class BodyTest extends PHPUnit_Framework_TestCase {

  /** @test */
  public function should_be_able_to_create_new_body()
  {
    $body = new Body('http://shop.com', '123', ['name' => 'Philip Brown']);
    $this->assertInstanceOf('PhilipBrown\WorldPay\Body', $body);
    $this->assertEquals('http://shop.com', $body->route);
    $this->assertEquals('123', $body->signature);
    $this->assertEquals(['name' => 'Philip Brown'], $body->data);
  }

  /** @test */
  public function route_should_be_a_url()
  {
    $this->setExpectedException('Assert\AssertionFailedException');

    $body = new Body([], '123', []);
  }

  /** @test */
  public function signature_should_be_a_string()
  {
    $this->setExpectedException('Assert\AssertionFailedException');

    $body = new Body('http://shop.com', [], []);
  }

  /** @test */
  public function data_should_be_an_array()
  {
    $this->setExpectedException('Assert\AssertionFailedException');

    $body = new Body('http://shop.com', '123', '');
  }

}