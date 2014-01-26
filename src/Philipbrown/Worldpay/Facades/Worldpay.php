<?php namespace PhilipBrown\WorldPay\Facades;

use Illuminate\Support\Facades\Facade;

class WorldPay extends Facade {

  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'worldpay'; }

}
