<?php

use PhilipBrown\WorldPay\CartId;

class CartIdTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function should_create_new_cartid()
    {
        $id = CartId::set('My Shop');

        $this->assertInstanceOf('PhilipBrown\WorldPay\CartId', $id);
        $this->assertEquals('My Shop', (string) $id);
    }

    /** @test */
    public function should_only_accept_strings()
    {
        $this->setExpectedException('Assert\AssertionFailedException');

        $id = CartId::set([]);
    }
}
