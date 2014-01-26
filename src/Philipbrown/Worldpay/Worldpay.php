<?php namespace PhilipBrown\WorldPay;

class WorldPay {

  /**
   * @var array
   */
  protected $config = array('env' => 'development');

  /**
   * Set Config
   *
   * @param array
   * @return void
   */
  public function setConfig($config)
  {
    $this->config = array_merge($this->config, $config);
  }

  /**
   * Request
   *
   * @return WorldPay\Request
   */
  public function request($parameters = null)
  {
    return new Request($this->config, $parameters);
  }

  /**
   * Response
   *
   * @return WorldPay\Response
   */
  public function response($parameters = null)
  {
    return new Response($this->config, $parameters);
  }

}
