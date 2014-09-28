<?php namespace PhilipBrown\WorldPay\Tests;

use PhilipBrown\WorldPay\InstId;

class InstIdTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function should_create_new_instid()
    {
        $id = InstId::set('12345');

        $this->assertInstanceOf('PhilipBrown\WorldPay\InstId', $id);
        $this->assertEquals('12345', (string) $id);
    }

    /** @test */
    public function should_only_accept_strings()
    {
        $this->setExpectedException('Assert\AssertionFailedException');

        $id = InstId::set([]);
    }
}
