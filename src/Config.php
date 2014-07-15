<?php namespace PhilipBrown\WorldPay;

class Config {

  /**
   * The Installation Id
   *
   * @var string
   */
  public $instId;

  /**
   * The Cart Id
   *
   * @var string
   */
  public $cartId;

  /**
   * The Installation password
   *
   * @var string
   */
  public $password;

  /**
   * The environment
   *
   * @var string
   */
  public $env;

  /**
   * The Callback URL
   *
   * @var string
   */
  public $callback;

  /**
   * Create a new instance of Config
   *
   * @param string $instId
   * @param string $cartId
   * @param string $password
   * @param string $env
   * @param string $callback;
   * @return void
   */
  public function __construct($instId, $cartId, $password, $env = 'development', $callback = null)
  {
    $this->instId = $instId;
    $this->cartId = $cartId;
    $this->password = $password;
    $this->env = $env;
    $this->callback = $callback;
  }

}
