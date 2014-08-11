<?php namespace PhilipBrown\WorldPay;

use Assert\Assertion;

class Callback {

  /**
   * @var string
   */
  protected $url;

  /**
   * @param string $url
   * @return void
   */
  private function __construct($url)
  {
    Assertion::url($url);

    $this->url = $url;
  }

  /**
   * Set the Callback
   *
   * @param string $url
   * @return PhilipBrown\WorldPay\Callback
   */
  public static function set($url)
  {
    return new Callback($url);
  }

  /**
   * Return the Callback when cast to string
   *
   * @return string
   */
  public function __toString()
  {
    return $this->url;
  }

}
