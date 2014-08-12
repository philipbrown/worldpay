<?php namespace PhilipBrown\WorldPay;

use Assert\Assertion;

class Money {

  /**
   * @var string
   */
  private $value;

  /**
   * @param string $value
   */
  private function __construct($value)
  {
    Assertion::integer($value);

    $this->value = $value;
  }

  /**
   * Set the Money value
   *
   * @param string $value
   * @return Money
   */
  public static function set($value)
  {
    return new Money($value);
  }

  /**
   * Format the Money value
   *
   * @return string
   */
  private function format()
  {
    return number_format(($this->value / 100), 2);
  }

  /**
   * Return the Money value when cast to string
   *
   * @return string
   */
  public function __toString()
  {
    return $this->format();
  }

}
