<?php

use PhilipBrown\WorldPay\Response;

class ResponseTest extends PHPUnit_Framework_TestCase {

  public function testResponseIsValidWithCorrectPassword()
  {
    $response = new Response('qwerty', ['callbackPW' => 'qwerty']);
    $this->assertTrue($response->isValid());
  }

  public function testResponseIsSuccess()
  {
    $response = new Response('', ['transStatus' => 'Y']);
    $this->assertTrue($response->isSuccess());
  }

  public function testResponseIsCancelled()
  {
    $response = new Response('', ['transStatus' => 'C']);
    $this->assertTrue($response->isCancelled());
  }

  public function testResponseIsProduction()
  {
    $response = new Response('', ['testMode' => '0']);
    $this->assertTrue($response->isProduction());
  }

  public function testResponseIsDevelopment()
  {
    $response = new Response('', ['testMode' => '100']);
    $this->assertTrue($response->isDevelopment());
  }

  public function testDynamicallyGetParameter()
  {
    $response = new Response('', ['name' => 'Philip Brown']);
    $this->assertEquals('Philip Brown', $response->name);
  }

}
