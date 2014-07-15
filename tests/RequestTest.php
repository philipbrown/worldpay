<?php

use PhilipBrown\WorldPay\Request;

class RequestTest extends PHPUnit_Framework_TestCase {

  public function testGetEndPoint()
  {
    $this->assertEquals('https://secure.worldpay.com/wcc/purchase', $this->standard()->getEndPoint());
  }

  public function testGetCustomEndPoint()
  {
    $this->assertEquals('http://hit-my-callback.com', $this->custom()->getEndPoint());
  }

  /**
   * @expectedException Exception
   */
  public function testMissingCallbackExcpetion()
  {
    $request = new Request('testing','1234','Amazong','GBP','9.99','super_secret',['name' => 'Philip Brown']);
    $request->getEndPoint();
  }

  public function testSettingCustomSignatureFields()
  {
    $request = $this->standard();
    $request->setSignatureFields(['name']);
    $this->assertEquals(['instId', 'cartId', 'currency', 'amount', 'name'], $request->getSignatureFields());
  }

  public function testSignatureProducesMD5()
  {
    $this->assertEquals('2a3887bf8ca8a686f7f128ddd9a50bc5', $this->standard()->getSignature());
  }

  public function testGettingRequestParameters()
  {
    $this->assertEquals([
      'instId' => '1234',
      'cartId' => 'Amazong',
      'currency' => 'GBP',
      'amount' => '9.99',
      'name' => 'Philip Brown'
    ], $this->standard()->getRequestParameters());
  }

  public function testSendRequest()
  {
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $this->standard()->send());
  }

  public function testPrepareRequest()
  {
    $request = $this->standard()->prepare();
    $this->assertEquals('https://secure.worldpay.com/wcc/purchase', $request->endpoint);
    $this->assertEquals('2a3887bf8ca8a686f7f128ddd9a50bc5', $request->signature);
    $this->assertEquals([
      'instId' => '1234',
      'cartId' => 'Amazong',
      'currency' => 'GBP',
      'amount' => '9.99',
      'name' => 'Philip Brown'
    ], $request->data);
  }

  protected function standard()
  {
    return new Request(
      'production',
      '1234',
      'Amazong',
      'GBP',
      '9.99',
      'super_secret',
      ['name' => 'Philip Brown']
    );
  }

  protected function custom()
  {
    return new Request(
      'testing',
      '1234',
      'Amazong',
      'GBP',
      '9.99',
      'super_secret',
      ['name' => 'Philip Brown'],
      'http://hit-my-callback.com'
    );
  }

}
