<?php namespace PhilipBrown\WorldPay;

use Exception;

class Response {

  /**
   * The response password
   *
   * @var string
   */
  private $password;

  /**
   * The response attributes
   *
   * @var array
   */
  private $attributes;

  /**
   * Create a new Response instance
   *
   * @param string $password
   * @param array $attributes
   * @return void
   */
  public function __construct($password, array $attributes)
  {
    $this->password = $password;
    $this->attributes = $attributes;
  }

  /**
   * Assert if the response is valid
   *
   * @return bool
   */
  public function isValid()
  {
    return $this->attributes['callbackPW'] == $this->password;
  }

  /**
   * Assert if the request was a success
   *
   * @return bool
   */
  public function isSuccess()
  {
    return $this->attributes['transStatus'] == 'Y';
  }

  /**
   * Assert if the request is valid
   *
   * @return bool
   */
  public function isCancelled()
  {
    return $this->attributes['transStatus'] == 'C';
  }

  /**
   * Assert if the response is live
   *
   * @return bool
   */
  public function isProduction()
  {
    return $this->attributes['testMode'] == 0;
  }

  /**
   * Assert if the response is development
   *
   * @return bool
   */
  public function isDevelopment()
  {
    return $this->attributes['testMode'] == 100;
  }

  /**
   * Dynamically get an attribute
   *
   * @param string $key
   * @return mixed
   */
  public function __get($key)
  {
    if(isset($this->attributes[$key]))
    {
      return $this->attributes[$key];
    }

    throw new Exception("{$key} is not a valid property");
  }

}
