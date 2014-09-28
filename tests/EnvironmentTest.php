<?php

use PhilipBrown\WorldPay\Environment;

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function should_create_a_new_production_environment()
    {
        $environment = Environment::set('production');

        $this->assertInstanceOf('PhilipBrown\WorldPay\Environment', $environment);
        $this->assertEquals(0, $environment->asInt());
        $this->assertEquals('production', (string) $environment);
    }

    /** @test */
    public function should_create_a_new_development_environment()
    {
        $environment = Environment::set('development');

        $this->assertInstanceOf('PhilipBrown\WorldPay\Environment', $environment);
        $this->assertEquals(100, $environment->asInt());
        $this->assertEquals('development', (string) $environment);
    }

    /** @test */
    public function should_create_a_new_local_environment()
    {
        $environment = Environment::set('local');

        $this->assertInstanceOf('PhilipBrown\WorldPay\Environment', $environment);
        $this->assertEquals(100, $environment->asInt());
        $this->assertEquals('local', (string) $environment);
    }

    /** @test */
    public function environment_should_be_a_string()
    {
        $this->setExpectedException('Assert\AssertionFailedException');

        $environment = Environment::set(['env' => 'production']);
    }
}
