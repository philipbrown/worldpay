<?php namespace PhilipBrown\WorldPay;

use Assert\Assertion;

class Environment {

  /**
   * @var string
   */
  private $env;

  /**
   * @param string $env
   * @return void
   */
  private function __construct($env)
  {
    Assertion::string($env);

    $this->env = $env;
  }

  /**
   * Set the Environment
   *
   * @param string $env
   * @return Environment
   */
  public static function set($env)
  {
    return new Environment($env);
  }

  /**
   * Return the environment as an integer
   *
   * @return int
   */
  public function asInt()
  {
    if($this->env === 'production') return 0;

    return 100;
  }

  /**
   * Return the Environment when cast to string
   *
   * @return string
   */
  public function __toString()
  {
    return $this->env;
  }

}
