<?php namespace PhilipBrown\WorldPay;

class Environment {

  /**
   * The environment string
   *
   * @var string
   */
  private $env;

  /**
   * Create a new Environment
   *
   * @param string $env
   * @return void
   */
  public function __construct($env)
  {
    $this->env = $env;
  }

  /**
   * Return the environment as a string
   *
   * @return string
   */
  public function asString()
  {
    return $this->env;
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
   * Return the string version when cast to string
   *
   * @return string
   */
  public function __toString()
  {
    return $this->env;
  }

}
