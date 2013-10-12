<?php namespace WorldPay;

use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractWorldPay {

  /**
   * @var Symfony\Component\HttpFoundation\ParameterBag;
   */
  protected $parameters;

  /**
   * Create object with new instance of ParameterBag
   */
  public function __construct()
  {
    $this->parameters = new ParameterBag;
  }

  /**
   * Initialise the object with parameters
   *
   * Set the default parameters first and then set the
   * user given parameters second to allow for overrides
   *
   * @var $properties array
   */
  public function initialise($parameters)
  {
    foreach($this->getDefaultParameters() as $param)
    {
      $this->setParameter($param, null);
    }

    foreach($parameters as $key => $value)
    {
      $this->setParameter($key, $value);
    }
  }

  /**
   * Determines if this key has a setter or getter method
   *
   * @var string $type ('get'|'set')
   * @var string $key
   * @return bool
   */
  protected function hasMethod($type = 'get', $key)
  {
    return method_exists($this, Helper::convertParamMethodName($type, $key));
  }

  /**
   * Is Custom Param?
   *
   * Determines if this is a custom parameter
   *
   * Custom parameters begin with 'MC_' or 'C_'
   *
   * @var string $key
   * @return bool
   */
  protected function isCustomParam($key)
  {
    if(substr($key, 0, 3) == 'MC_' || substr($key, 0, 2) == 'C_')
    {
      return true;
    }
    return false;
  }

  /**
   * Set Parameter
   *
   * Set the parameter if it has a setter method
   * or if it is a Custom parameter.
   *
   * @var string $key
   * @var mixed $value
   * @return void
   */
  protected function setParameter($key, $value)
  {
    if($this->hasMethod('set', $key))
    {
      $method = Helper::convertParamMethodName('set', $key);
      $this->{$method}($value);
    }
    if($this->isCustomParam($key))
    {
      $this->setCustomParam($key, $value);
    }
  }

  /**
   * Get Parameter
   *
   * Get the parameter if it has a getter method
   * or if it is a Custom parameter
   *
   * @var string $key
   * @return string
   */
  protected function getParameter($key)
  {
    if($this->hasMethod('get', $key))
    {
      $method = Helper::convertParamMethodName('get', $key);
      return $this->{$method}();
    }
    if($this->isCustomParam($key))
    {
      return $this->getCustomParam($key);
    }
  }

  /**
   * Set Custom Param
   *
   * Called when an MC_ or C_ parameter is set
   *
   * @var string $key
   * @var string $value
   * @return void
   */
  protected function setCustomParam($key, $value)
  {
    $this->parameters->set($key, $value);
  }

  /**
   * Get Custom Param
   *
   * Called when a MC_ or C_ parameter is requested
   *
   * @var string $key
   * @return string
   */
  protected function getCustomParam($key)
  {
    return $this->parameters->get($key);
  }

  /**
   * Dynamically set attributes on the object
   *
   * @var string $key
   * @var string $value
   * @return void
   */
  public function __set($key, $value)
  {
    $this->setParameter($key, $value);
  }

  /**
   * Dynamically get attributes on the object
   *
   * @var string $key
   * @return string
   */
  public function __get($key)
  {
    return $this->getParameter($key);
  }

}
