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

}
