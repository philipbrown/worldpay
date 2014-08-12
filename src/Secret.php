<?php namespace PhilipBrown\WorldPay;

use Assert\Assertion;

class Secret {

  /**
   * @var string
   */
  protected $secret;

  /**
   * @param string $secret
   * @return void
   */
  private function __construct($secret)
  {
    Assertion::string($secret);

    $this->secret = $secret;
  }

  /**
   * Set the Secret
   *
   * @param string $secret
   * @return Secret
   */
  public static function set($secret)
  {
    return new Secret($secret);
  }

  /**
   * Return the Secret when cast to string
   *
   * @return string
   */
  public function __toString()
  {
    return $this->secret;
  }

}
