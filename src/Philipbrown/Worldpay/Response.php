<?php namespace PhilipBrown\WorldPay;

use Carbon\Carbon;

class Response extends AbstractWorldPay {

  /**
   * @var array
   */
  protected $config;

  /**
   * Construct
   *
   * @param array $config
   * @param array $parameters
   */
  public function __construct($config, $parameters)
  {
    parent::__construct();

    $this->config = $config;

    $this->initialise($parameters);

    $this->parameters->set('timestamp', Carbon::now());

    if($this->isCustomEnv())
    {
      $this->fakeWorldPayRequest($parameters);
    }
  }

  /**
   * Get Default Parameters
   *
   * @return array
   */
  public function getDefaultParameters()
  {
    return array(
      'testMode',
      'callbackPW',
      'transStatus',
      'transId',
      'instId',
      'cartId',
      'amount',
      'cardType',
      'currency',
      'ipAddress',
      'desc',
      'compName',
      'futurePayId',
      'name',
      'address1',
      'address2',
      'address3',
      'town',
      'postcode',
      'tel',
      'fax',
      'country',
      'countryString',
      'email',
    );
  }

  /**
   * Is Valid?
   *
   * Ensure the Callback Password is correct
   *
   * @param string $password
   * @return bool
   */
  public function isValid($password)
  {
    return $this->getPasswordParameter() == $password;
  }

  /**
   * Is Success?
   *
   * Was the transaction successful?
   *
   * @return bool
   */
  public function isSuccess()
  {
    return $this->getTransactionStatusParameter() == 'Y';
  }

  /**
   * Is Cancelled?
   *
   * Was the transaction cancelled by the customer
   *
   * @return bool
   */
  public function isCancelled()
  {
    return $this->getTransactionStatusParameter() == 'C';
  }

  /**
   * Is FuturePay?
   *
   * Is this a FuturePay transaction?
   *
   * @return bool
   */
  public function isFuturePay()
  {
    return $this->getFuturePayIdParameter() != null;
  }

  /**
   * Is Production
   *
   * Is this a live transaction?
   *
   * @return bool
   */
  public function isProduction()
  {
    return $this->getTestModeParameter() == 0;
  }

  /**
   * Is Development
   *
   * Is this a dev transaction?
   *
   * @return bool
   */
  public function isDevelopment()
  {
    return $this->getTestModeParameter() == 100;
  }

  /**
   * Get Timestamp Parameter
   *
   * @return string
   */
  protected function getTimestampParameter()
  {
    return $this->parameters->get('timestamp');
  }

  /**
   * Set Test Mode Parameter
   *
   * @param $value string
   * @return void
   */
  protected function setTestModeParameter($value)
  {
    $this->parameters->set('testMode', $value);
  }

  /**
   * Set Callback Password Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setCallbackPWParameter($value)
  {
    $this->parameters->set('callbackPW', $value);
  }

  /**
   * Set Transaction Status Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setTransStatusParameter($value)
  {
    $this->parameters->set('transStatus', $value);
  }

  /**
   * Set Transaction Id Parameter
   *
   * @param string $string
   * @return void
   */
  protected function setTransIdParameter($value)
  {
    $this->parameters->set('transId', $value);
  }

  /**
   * Set IP Address Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setIpAddressParameter($value)
  {
    $this->parameters->set('ipAddress', $value);
  }

  /**
   * Set Company Name Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setCompNameParameter($value)
  {
    $this->parameters->set('compName', $value);
  }

  /**
   * Set FuturePay Id Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setFuturePayIdParameter($value)
  {
    $this->parameters->set('futurePayId', $value);
  }

  /**
   * Set Address Line 1 Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setAddress1Parameter($value)
  {
    $this->parameters->set('address1', $value);
  }

  /**
   * Set Address Line 2 Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setAddress2Parameter($value)
  {
    $this->parameters->set('address2', $value);
  }

  /**
   * Set Address Line 3 Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setAddress3Parameter($value)
  {
    $this->parameters->set('address3', $value);
  }

  /**
   * Set Desc Parameter
   *
   * @param $value string
   * @return void
   */
  protected function setDescParameter($value)
  {
    $this->parameters->set('desc', $value);
  }

  /**
   * Set Tel Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setTelParameter($value)
  {
    $this->parameters->set('tel', $value);
  }

  /**
   * Set Country String Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setCountryStringParameter($value)
  {
    $this->parameters->set('countryString', $value);
  }

  /**
   * Set Card Type Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setCardTypeParameter($value)
  {
    $this->parameters->set('cardType', $value);
  }

  /**
   * Get Inst Id Parameter
   *
   * @return string
   */
  protected function getInstIdParameter()
  {
    return $this->parameters->get('instId');
  }

  /**
   * Get Cart Id Parameter
   *
   * @return string
   */
  protected function getCartIdParameter()
  {
    return $this->parameters->get('cartId');
  }

  /**
   * Get Amount Parameter
   *
   * @return string
   */
  protected function getAmountParameter()
  {
    return $this->parameters->get('amount');
  }

  /**
   * Get Currency Parameter
   *
   * @param string $value
   * @return void
   */
  protected function getCurrencyParameter()
  {
    return $this->parameters->get('currency');
  }

  /**
   * Get Description Parameter
   *
   * @return string
   */
  protected function getDescriptionParameter()
  {
    return $this->parameters->get('desc');
  }

  /**
   * Get Name Parameter
   *
   * @return string
   */
  protected function getNameParameter()
  {
    return $this->parameters->get('name');
  }

  /**
   * Get Town Parameter
   *
   * @return string
   */
  protected function getTownParameter()
  {
    return $this->parameters->get('town');
  }

  /**
   * Get Postcode Parameter
   *
   * @return string
   */
  protected function getPostcodeParameter()
  {
    return $this->parameters->get('postcode');
  }

  /**
   * Get Country Parameter
   *
   * @return string
   */
  protected function getCountryParameter()
  {
    return $this->parameters->get('country');
  }

  /**
   * Get Email Paramater
   *
   * @return string
   */
  protected function getEmailParameter()
  {
    return $this->parameters->get('email');
  }

  /**
   * Get Environment Parameter
   *
   * @return string
   */
  protected function getEnvironmentParameter()
  {
    return Translate::getTestMode($this->parameters->get('testMode'));
  }

  /**
   * Get Test Mode Parameter
   *
   * @return string
   */
  protected function getTestModeParameter()
  {
    return $this->parameters->get('testMode');
  }

  /**
   * Get Password Parameter
   *
   * @return string
   */
  protected function getPasswordParameter()
  {
    return $this->parameters->get('callbackPW');
  }

  /**
   * Get Transaction Id Parameter
   *
   * @return string
   */
  protected function getTransactionIdParameter()
  {
    return $this->parameters->get('transId');
  }

  /**
   * Get Transaction Status Parameter
   *
   * @return string
   */
  protected function getTransactionStatusParameter()
  {
    return $this->parameters->get('transStatus');
  }

  /**
   * Get Card Type Parameter
   *
   * @param string $value
   * @return void
   */
  protected function getCardTypeParameter()
  {
    return $this->parameters->get('cardType');
  }

  /**
   * Get IP Address Parameter
   *
   * @return string
   */
  protected function getIpAddressParameter()
  {
    return $this->parameters->get('ipAddress');
  }

  /**
   * Get Company Name Parameter
   *
   * @return string
   */
  protected function getCompanyNameParameter()
  {
    return $this->parameters->get('compName');
  }

  /**
   * Get FuturePay Id Parameter
   *
   * @return void
   */
  protected function getFuturePayIdParameter()
  {
    return $this->parameters->get('futurePayId');
  }

  /**
   * Get Address Line 1 Parameter
   *
   * @return string
   */
  protected function getAddressLine1Parameter()
  {
    return $this->parameters->get('address1');
  }

  /**
   * Get Address Line 2 Parameter
   *
   * @return string
   */
  protected function getAddressLine2Parameter()
  {
    return $this->parameters->get('address2');
  }

  /**
   * Get Address Line 3 Parameter
   *
   * @return string
   */
  protected function getAddressLine3Parameter()
  {
    return $this->parameters->get('address3');
  }

  /**
   * Get Telephone Parameter
   *
   * @return string
   */
  protected function getTelephoneParameter()
  {
    return $this->parameters->get('tel');
  }

  /**
   * Get Fax Parameter
   *
   * @return string
   */
  protected function getFaxParameter()
  {
    return $this->parameters->get('fax');
  }

  /**
   * Get Country String Parameter
   *
   * @return string
   */
  protected function getCountryStringParameter()
  {
    return $this->parameters->get('countryString');
  }

  /**
   * Fake WorldPay Request
   *
   * @param array $parameters
   */
  protected function fakeWorldPayRequest($parameters)
  {
    $parameters = array_merge($this->getDefaultResponseParameters(), $parameters);
    $this->parameters->set('testMode', 100);
    $this->parameters->set('callbackPW', $this->config['password']);
    $this->parameters->set('transStatus', 'Y');
    $this->parameters->set('transId', rand(1000000000, 2000000000));
    $this->parameters->set('cardType', $parameters['payment_type']);
    $this->parameters->set('ipAddress', '123.456.789');
    $this->parameters->set('desc', $parameters['description']);
    $this->parameters->set('address1', $parameters['address_line_1']);
    $this->parameters->set('address2', $parameters['address_line_2']);
    $this->parameters->set('address3', $parameters['address_line_3']);
    $this->parameters->set('tel', $parameters['telephone']);
    $this->parameters->set('futurePayId', $this->isFakeFuturePay($parameters));
  }

  /**
   * Is Fake FuturePay
   *
   * @param array $parameters
   * @return mixed
   */
  protected function isFakeFuturePay($parameters)
  {
    if(isset($parameters['futurePay_type']))
    {
      return rand(10000000, 10999999);
    }
    return null;
  }

  /**
   * Get Default Response Parameters
   *
   * @return array
   */
  protected function getDefaultResponseParameters()
  {
    return array(
      'payment_type' => null,
      'description' => null,
      'address_line_1' => null,
      'address_line_2' => null,
      'address_line_3' => null,
      'telephone' => null
    );
  }

}
