<?php namespace Philipbrown\Worldpay\Test;

use Philipbrown\Worldpay;

class RequestTest extends TestCase {

  public function testSettingRequestParameters()
  {
    $wp = $this->getWorldpay();
    $request = $wp->request($this->getNormalRequest());
    $r = $request->prepare();
    $this->assertEquals('123456789', $r['data']['instId']);
    $this->assertEquals('my_shop', $r['data']['cartId']);
    $this->assertEquals('GBP', $r['data']['currency']);
    $this->assertEquals('99.99', $r['data']['amount']);
    $this->assertEquals(100, $r['data']['testMode']);
    $this->assertEquals('Philip Brown', $r['data']['name']);
    $this->assertEquals('phil@ipbrown.com', $r['data']['email']);
    $this->assertEquals('101 Blah Blah Lane', $r['data']['address1']);
    $this->assertEquals('My Street', $r['data']['address2']);
    $this->assertEquals('My Place', $r['data']['address3']);
    $this->assertEquals('London', $r['data']['town']);
    $this->assertEquals('E20 123', $r['data']['postcode']);
    $this->assertEquals('GB', $r['data']['country']);
    $this->assertEquals('123456789', $r['data']['telephone']);
    $this->assertEquals('987654321', $r['data']['fax']);
    $this->assertEquals('123', $r['data']['MC_customer_id']);
    $this->assertEquals('456', $r['data']['CM_order_id']);
  }

  public function testSettingFuturePayRequestParameters()
  {
    $wp = $this->getWorldpay();
    $request = $wp->request($this->getFuturePayRequest());
    $r = $request->prepare();
    $this->assertEquals('regular', $r['data']['futurePayType']);
    $this->assertEquals(0, $r['data']['option']);
    $this->assertEquals(1, $r['data']['intervalMult']);
    $this->assertEquals(4, $r['data']['intervalUnit']);
    $this->assertEquals($this->getTomorrow(), $r['data']['startDate']);
  }

  public function testSignature()
  {
    $wp = $this->getWorldpay();
    $request = $wp->request($this->getNormalRequest());
    $request->setSecret('my_secret');
    $r = $request->prepare();
    $this->assertEquals(md5('my_secret:123456789:my_shop:GBP:99.99'), $r['signature']);
  }

  public function testSignatureWithCustomFields()
  {
    $wp = $this->getWorldpay();
    $request = $wp->request($this->getNormalRequest());
    $request->setSecret('my_secret');
    $request->setSignatureFields(array('email'));
    $r = $request->prepare();
    $this->assertEquals(md5('my_secret:123456789:my_shop:GBP:99.99:phil@ipbrown.com'), $r['signature']);
  }

  public function testNormalRequestSend()
  {
    $wp = $this->getWorldpay();
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $wp->request($this->getNormalRequest())->send());
  }

  /**
   * @expectedException        Philipbrown\Worldpay\Exceptions\InvalidRequestException
   * @expectedExceptionMessage Invalid FuturePay type
   */
  public function testInvalidFuturePayType()
  {
    $wp = $this->getWorldpay();
    $request = $wp->request(array(
      'futurepay_type' => 'oh noes'
    ));
    $request->prepare();
  }

}
