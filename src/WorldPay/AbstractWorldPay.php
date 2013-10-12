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

}
