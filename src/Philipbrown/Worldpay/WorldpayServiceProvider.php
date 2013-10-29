<?php namespace Philipbrown\Worldpay;

use Illuminate\Support\ServiceProvider;

class WorldpayProvider extends ServiceProvider {

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
      return new Worldpay;
    });
    $this->app->booting(function()
    {
      $loader = \Illuminate\Foundation\AliasLoader::getInstance();
      $loader->alias('Worldpay', 'Philipbrown\Worldpay\Facades\Worldpay');
    });
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return array('Worldpay');
  }

}
