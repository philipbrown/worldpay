<?php namespace PhilipBrown\WorldPay;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class WorldPayServiceProvider extends ServiceProvider {

  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = false;

  /**
   * Bootstrap the application events.
   *
   * @return void
   */
  public function boot()
  {
    $this->package('philipbrown/worldpay');
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app['worldpay'] = $this->app->share(function($app)
    {
      $wp = new WorldPay;

      $wp->setConfig(Config::get('worldpay'));

      return $wp;
    });

    $this->app->booting(function()
    {
      $loader = AliasLoader::getInstance();
      $loader->alias('WorldPay', 'PhilipBrown\WorldPay\Facades\WorldPay');
    });
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return array('WorldPay');
  }

}
