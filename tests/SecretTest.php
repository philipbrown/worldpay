<?php

use PhilipBrown\WorldPay\Secret;

class SecretTest extends PHPUnit_Framework_TestCase {

  /** @test */
  public function should_create_new_secret()
  {
    $secret = Secret::set('my secret...');

    $this->assertInstanceOf('PhilipBrown\WorldPay\Secret', $secret);
    $this->assertEquals('my secret...', (string) $secret);
  }

  /** @test */
  public function should_only_accept_strings()
  {
    $this->setExpectedException('Assert\AssertionFailedException');

    $id = Secret::set([]);
  }

}
