<?php namespace WorldPay;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\RedirectResponse as HttpRedirectResponse;

class Request extends AbstractWorldPay {

  /**
   * @var array
   */
  protected $endpoints = array(
    'production'  => 'https://secure.worldpay.com/wcc/purchase',
    'development' => 'https://secure-test.worldpay.com/wcc/purchase'
  );

  /**
   * @var array
   */
  protected $config;

  /**
   * @var string
   */
  protected $secret;

  /**
   * @var array
   */
  protected $fields;

  /**
   * Construct
   *
   * @var array $config
   * @var array $parameters
   * @return void
   */
  public function __construct($config, $parameters)
  {
    parent::__construct();

    $this->config = $config;

    $this->initialise($parameters);
  }

  /**
   * Set Secret
   *
   * @var string $secret
   * @return void
   */
  public function setSecret($secret)
  {
    $this->secret = $secret;
  }

  /**
   * Set Signature Fields
   *
   * @var array $fields
   * @return void
   */
  public function setSignatureFields($fields)
  {
    $this->fields = $fields;
  }

  /**
   * Get Default Parameters
   *
   * @return array
   */
  protected function getDefaultParameters()
  {
    return array('instId', 'cartId', 'currency', 'amount');
  }

  /**
   * Get Default FuturePay Parameters
   *
   * @return array
   */
  protected function getDefaultFuturePayParameters()
  {
    return array('futurePayType');
  }

  /**
   * Get Endpoint
   *
   * Returns the correct endpoint based on the environement
   * If a custom environment is set, the request URL must be
   * set in the configuration array.
   *
   * @return string
   */
  protected function getEndpoint()
  {
    $env = ($this->config['env']) ? $this->config['env'] : 'development';

    if($env == 'production' || $env == 'development')
    {
      return $this->endpoints[$env];
    }

    return $this->config['env']['url'];
  }

}
