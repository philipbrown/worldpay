<?php namespace WorldPay;

class Response extends AbstractWorldPay {

  /**
   * @var array
   */
  protected $config;

  /**
   * Construct
   *
   * @var array $config
   * @var array $parameters
   */
  public function __construct($config, $parameters)
  {
    parent::__construct();

    $this->config = $config;

    $this->initialise($parameters);

    $this->parameters->set('timestamp', Carbon::now());
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
      'amountString',
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
   * @var string $password
   * @return bool
   */
  public function isValid($password)
  {
    return $this->getCallbackPWParameter() == $password;
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
    return $this->getTransStatusParameter() == 'Y';
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
    return $this->getTransStatusParameter() == 'C';
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

}
