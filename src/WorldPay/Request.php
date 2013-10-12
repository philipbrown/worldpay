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
   * Send
   *
   * Send the request straight to WorldPay
   *
   * @return HttpRedirectResponse
   */
  public function send()
  {
    return HttpRedirectResponse::create($this->createRequest())->send();
  }

  /**
   * Prepare
   *
   * Prepare the request so that it can be sent asynchronously
   *
   * @return array
   */
  public function prepare()
  {
    return array(
      'endpoint'  => $this->getEndPoint(),
      'data'      => $this->getData()
    );
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

    /**
     * TO DO:
     * Add exception environment hasn't been set correctly
     */
    return $this->config['env']['url'];
  }

  /**
   * Get Data
   *
   * Return all the parameters from the ParameterBag
   *
   * @return array
   */
  protected function getData()
  {
    return $this->parameters->all();
  }

  /**
   * Get Signature Fields
   *
   * @return array
   */
  protected function getSignatureFields()
  {
    $fields = ($this->fields) ? $this->fields : array();

    $default = array_merge($this->getDefaultParameters(), $fields);

    if($this->parameters->has('futurePayType'))
    {
      return array_merge($default, $this->getDefaultFuturePayParameters());
    }

    return $default;
  }

  /**
   * Get Signature Fields Data
   *
   * @var array $fields
   * @var array $data
   * @return array
   */
  protected function getSignatureFieldsData($fields, $data)
  {
    foreach($fields as $field)
    {
      $signature[$field] = $data[$field];
    }

    return $signature;
  }

  /**
   * Get Signature
   *
   * @return string
   */
  protected function getSignature()
  {
    $fields = $this->getSignatureFieldsData($this->getSignatureFields(), $this->getData());

    return md5($this->secret.':'.implode(':', $fields));
  }

  /**
   * Get Query String
   *
   * @return string
   */
  protected function getQueryString()
  {
    return '?signature='.$this->getSignature().'?'.http_build_query($this->getData());
  }

  /**
   * Create Request
   *
   * Concatenate the endpoint and the data to send
   *
   * @return string
   */
  protected function createRequest()
  {
    return $this->getEndpoint().$this->getQueryString();
  }

}
