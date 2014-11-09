<?php namespace PhilipBrown\WorldPay\Tests;

use PhilipBrown\WorldPay\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function should_create_a_new_response()
    {
        $response = new Response('', []);

        $this->assertInstanceOf('PhilipBrown\WorldPay\Response', $response);
    }

    /** @test */
    public function should_have_access_to_response_attributes()
    {
        $response = new Response('', ['name' => 'Philip Brown']);

        $this->assertEquals('Philip Brown', $response->name);
    }

    /** @test */
    public function should_be_valid_with_correct_password()
    {
        $response = new Response('qwerty', ['callbackPW' => 'qwerty']);

        $this->assertTrue($response->isValid());
    }

    /** @test */
    public function should_be_invalid_with_incorrect_password()
    {
        $response = new Response('qwerty', ['callbackPW' => 'dvorak']);

        $this->assertFalse($response->isValid());
    }

    /** @test */
    public function should_be_a_success()
    {
        $response = new Response('', ['transStatus' => 'Y']);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function should_not_be_a_success()
    {
        $response = new Response('', ['transStatus' => 'C']);

        $this->assertFalse($response->isSuccess());
    }

    /** @test */
    public function should_be_cancelled()
    {
        $response = new Response('', ['transStatus' => 'C']);

        $this->assertTrue($response->isCancelled());
    }

    /** @test */
    public function should_not_be_cancelled()
    {
        $response = new Response('', ['transStatus' => 'Y']);

        $this->assertFalse($response->isCancelled());
    }

    /** @test */
    public function should_be_in_production()
    {
        $response = new Response('', ['testMode' => '0']);

        $this->assertTrue($response->isProduction());
    }

    /** @test */
    public function should_not_be_in_production()
    {
        $response = new Response('', ['testMode' => '100']);

        $this->assertFalse($response->isProduction());
    }

    /** @test */
    public function should_be_in_development()
    {
        $response = new Response('', ['testMode' => '100']);

        $this->assertTrue($response->isDevelopment());
    }

    /** @test */
    public function should_not_be_in_development()
    {
        $response = new Response('', ['testMode' => '0']);

        $this->assertFalse($response->isDevelopment());
    }

    /** @test */
    public function should_return_null_for_invalid_property()
    {
        $response = new Response('', []);

        $this->assertEquals(null, $response->nope);
    }
}
