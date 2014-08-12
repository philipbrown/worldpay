<?php namespace PhilipBrown\WorldPay;

use Assert\Assertion;

class Body {

  /**
   * @var string
   */
  private $route;

  /**
   * @var string
   */
  private $signature;

  /**
   * @var array
   */
  private $data;

  /**
   * Create a new immutable Body instance
   *
   * @param string $route
   * @param string $signature
   * @param array $data
   * @return void
   */
  public function __construct($route, $signature, $data)
  {
    Assertion::url($route);
    Assertion::string($signature);
    Assertion::isArray($data);

    $this->route = $route;
    $this->signature = $signature;
    $this->data = $data;
  }

  /**
   * Get the private attributes
   *
   * @param string $key
   * @return mixed
   */
  public function __get($key)
  {
    if(property_exists($this, $key))
    {
      return $this->$key;
    }
  }

}
