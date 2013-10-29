<?php namespace Philipbrown\Worldpay\Facades;

use Illuminate\Support\Facades\Facade;

class Worldpay extends Facade {

  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'worldpay'; }

}
