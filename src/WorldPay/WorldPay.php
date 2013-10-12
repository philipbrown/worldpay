<?php namespace WorldPay;

class WorldPay {

  /**
   * @var array
   */
  protected $config;

  /**
   * Set Config
   *
   * @var array
   * @return void
   */
  public function setConfig($config)
  {
    $this->config = $config;
  }

  /**
   * Request
   *
   * @return Worldpay\Request
   */
  public function request($parameters = null)
  {
    return new Request($this->config, $parameters);
  }

  /**
   * Response
   *
   * @return Worldpay\Response
   */
  public function response($parameters = null)
  {
    return new Response($this->config, $parameters);
  }

}
