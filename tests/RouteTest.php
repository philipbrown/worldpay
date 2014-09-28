<?php namespace PhilipBrown\WorldPay\Tests;

use PhilipBrown\WorldPay\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function should_create_a_new_route()
    {
        $route = Route::set('http://shop.com/callbacks/worldpay');

        $this->assertInstanceOf('PhilipBrown\WorldPay\Route', $route);
        $this->assertEquals('http://shop.com/callbacks/worldpay', (string) $route);
    }

    /** @test */
    public function should_only_accept_valid_urls()
    {
        $this->setExpectedException('Assert\AssertionFailedException');

        $route = Route::set('...');
    }
}
