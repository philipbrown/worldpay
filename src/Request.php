<?php namespace PhilipBrown\WorldPay;

use StdClass;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Request {

  /**
   * The current environment
   *
   * @var string
   */
  private $env;

  /**
   * The customer callback URL
   *
   * @var string
   */
  private $callback;

  /**
   * The Installation Id
   *
   * @var string
   */
  private $instId;

  /**
   * The Cart Id
   *
   * @var string
   */
  private $cartId;

  /**
   * The Currency of the transaction
   *
   * @var string
   */
  private $currency;

  /**
   * The Amount of the transaction
   *
   * @var string
   */
  private $amount;

  /**
   * The secret to hash the request
   *
   * @var string
   */
  private $secret;

  /**
   * The request parameters
   *
   * @var array
   */
  private $parameters;

  /**
   * The default fields to use in the signiture
   *
   * @var array
   */
  private $defaults = ['instId', 'cartId', 'currency', 'amount'];

  /**
   * The WorldPay endpoints
   *
   * @var array
   */
  private $endpoints = [
    'production'  => 'https://secure.worldpay.com/wcc/purchase',
    'development' => 'https://secure-test.worldpay.com/wcc/purchase'
  ];

  /**
   * Create a new Request instance
   *
   * @param string $env
   * @param string $instId
   * @param string $cartId
   * @param string $currency
   * @param string $amount
   * @param string $secret
   * @param array $parameters
   * @param string $callback
   * @return void
   */
  public function __construct(
    Environment $env,
    $instId,
    $cartId,
    $currency,
    $amount,
    $secret,
    array $parameters,
    $callback = null)
  {
    $this->env = $env;
    $this->instId = $instId;
    $this->cartId = $cartId;
    $this->currency = $currency;
    $this->amount = $amount;
    $this->secret = $secret;
    $this->parameters = $parameters;
    $this->callback = $callback;
  }

  /**
   * Set the signature fields to use
   *
   * @param array $fields
   * @return void
   */
  public function setSignatureFields(array $fields)
  {
    $this->defaults = array_merge($this->defaults, $fields);
  }

  /**
   * Get the signature fields
   *
   * @return array
   */
  public function getSignatureFields()
  {
    return $this->defaults;
  }

  /**
   * Send the request to WorldPay
   *
   * @return Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function send()
  {
    return RedirectResponse::create(
      $this->getEndPoint() .
      '?signature='.$this->getSignature().'&'.http_build_query($this->getParameters())
    )->send();
  }

  /**
   * Return an object containing the request
   *
   * @return StdClass
   */
  public function prepare()
  {
    $request = new StdClass;
    $request->endpoint = $this->getEndPoint();
    $request->signature = $this->getSignature();
    $request->data = $this->getParameters();

    return $request;
  }

  /**
   * Get the parameters of the request
   *
   * @return array
   */
  public function getParameters()
  {
    return array_merge([
      'instId'    => $this->instId,
      'cartId'    => $this->cartId,
      'currency'  => $this->currency,
      'amount'    => $this->amount,
      'testMode'  => $this->env->asInt()
      ],
      $this->parameters
    );
  }

  /**
   * Get the endpoint for the current environment
   *
   * @return string
   */
  public function getEndPoint()
  {
    if(isset($this->endpoints[$this->env->asString()]))
    {
      return $this->endpoints[$this->env->asString()];
    }

    if($this->callback)
    {
      return $this->callback;
    }

    throw new Exception('A callback URL must be defined for a custom environment!');
  }

  /**
   * Get the signature as a MD5 hash
   *
   * @return string
   */
  public function getSignature()
  {
    $defaults = [
      'instId'    => $this->instId,
      'cartId'    => $this->cartId,
      'currency'  => $this->currency,
      'amount'    => $this->amount
    ];

    $parameters = array_intersect_key($this->parameters, array_flip($this->defaults));

    return md5($this->secret.':'.implode(':', array_merge($defaults, $parameters)));
  }

}

