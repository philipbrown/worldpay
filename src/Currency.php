<?php namespace PhilipBrown\WorldPay;

use Assert\Assertion;

class Currency {

  /**
   * @var string
   */
  private $name;

  /**
   * @var array
   */
  private static $currencies;

  /**
   * @param string $name
   */
  private function __construct($name)
  {
    if ( ! isset(self::$currencies))
    {
      self::$currencies = require __DIR__.'/currencies.php';
    }

    Assertion::keyExists(self::$currencies, $name);

    $this->name = $name;
  }

  /**
   * Set the Currency
   *
   * @param string $name
   * @return Currency
   */
  public static function set($name)
  {
    return new Currency($name);
  }

  /**
   * Return the Currency when cast to string
   *
   * @return string
   */
  public function __toString()
  {
    return $this->name;
  }

}
