<?php

use PhilipBrown\WorldPay\Money;
use PhilipBrown\WorldPay\InstId;
use PhilipBrown\WorldPay\CartId;
use PhilipBrown\WorldPay\Secret;
use PhilipBrown\WorldPay\Request;
use PhilipBrown\WorldPay\Currency;
use PhilipBrown\WorldPay\Callback;
use PhilipBrown\WorldPay\Environment;

class RequestTest extends PHPUnit_Framework_TestCase {

  /** @test */
  public function should_be_instance_of_request()
  {
    $this->assertInstanceOf('PhilipBrown\WorldPay\Request', $this->createTestingEnvironmentRequest());
  }

  /** @test */
  public function should_set_signature_fields_and_return_self()
  {
    $this->assertInstanceOf('PhilipBrown\WorldPay\Request', $this->createTestingEnvironmentRequest()->setSignatureFields(['name']));
  }

  /** @test */
  public function should_use_default_endpoint_for_default_environment()
  {
    $prepared = $this->createDevelopmentEnvironmentRequest()->prepare();

    $this->assertEquals('https://secure-test.worldpay.com/wcc/purchase', $prepared->endpoint);
  }

  /** @test */
  public function should_use_callback_for_custom_environment()
  {
    $prepared = $this->createTestingEnvironmentRequest()->prepare();

    $this->assertEquals('http://shop.test/callbacks/worldpay', $prepared->endpoint);
  }

  /** @test */
  public function request_should_generate_correct_signature()
  {
    $prepared = $this->createTestingEnvironmentRequest()->prepare();

    $this->assertEquals('7c98a959b30a6030b004cd6e845bcd87', $prepared->signature);
  }

  /** @test */
  public function request_should_generate_correct_signature_with_custom_fields()
  {
    $prepared = $this->createTestingEnvironmentRequest()->setSignatureFields(['name'])->prepare();

    $this->assertEquals('c50acdc5cc08d4af7f3ba296763f924e', $prepared->signature);
  }

  /** @test */
  public function request_should_gather_the_correct_data()
  {
    $prepared = $this->createTestingEnvironmentRequest()->prepare();

    $expected = [
      'instId'    => '123',
      'cartId'    => 'My shop',
      'currency'  => 'GB',
      'amount'    => '10.00',
      'testMode'  => 100,
      'name'      => 'Philip Brown'
    ];

    $this->assertEquals($expected, $prepared->data);
  }

  /** @test */
  public function sending_the_request_straight_worldpay_should_return_response()
  {
    $response = $this->createTestingEnvironmentRequest()->send();

    $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
  }

  /** @test */
  public function a_custom_environment_without_a_callback_should_throw_exception()
  {
    $this->setExpectedException('PhilipBrown\WorldPay\InvalidRequestException');

    $this->createCustomEnvironmentWithoutCallback()->prepare();
  }

  public function createTestingEnvironmentRequest()
  {
    return new Request(
      Environment::set('testing'),
      InstId::set('123'),
      CartId::set('My shop'),
      Secret::set('my secret'),
      Money::set(1000),
      Currency::set('GB'),
      ['name' => 'Philip Brown'],
      Callback::set('http://shop.test/callbacks/worldpay')
    );
  }

  public function createDevelopmentEnvironmentRequest()
  {
    return new Request(
      Environment::set('development'),
      InstId::set('123'),
      CartId::set('My shop'),
      Secret::set('my secret'),
      Money::set(1000),
      Currency::set('GB'),
      []
    );
  }

  public function createCustomEnvironmentWithoutCallback()
  {
    return new Request(
      Environment::set('testing'),
      InstId::set('123'),
      CartId::set('My shop'),
      Secret::set('my secret'),
      Money::set(1000),
      Currency::set('GB')
    );
  }

}
