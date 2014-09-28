<?php

use PhilipBrown\WorldPay\Password;

class PasswordTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function should_create_new_password()
    {
        $password = Password::set('my password...');

        $this->assertInstanceOf('PhilipBrown\WorldPay\Password', $password);
        $this->assertEquals('my password...', (string) $password);
    }

    /** @test */
    public function should_only_accept_strings()
    {
        $this->setExpectedException('Assert\AssertionFailedException');

        $id = Password::set([]);
    }
}
