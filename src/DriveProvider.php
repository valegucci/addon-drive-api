<?php
namespace Vale\Addons\Drive;

use Illuminate\Support\ServiceProvider;

class DriveProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @throws \Exception
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if (!str_contains($this->app->version(), 'Lumen')) {
                $this->publishes([
                    __DIR__ . '/../config/addon-drive.php' => config_path('addons/addon-drive.php'),
                ], 'config');
            }
        }
    }
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/addon-drive.php', 'addons.addon-drive');
        if (str_contains($this->app->version(), 'Lumen') && !property_exists($this->app, 'router')) {
            $router = $this->app;
        } else {
            $router = $this->app->make('router');
        }
        
        $router->group([
            'middleware' => [
                //turn on throttling
                'api',
            ],
            //setup custom prefix to url path
            'prefix' => 'api',
            //setup custom prefix to route name
            'as' => 'api.drive.',
            //shortcut to namespace
            'namespace' => 'Vale\Addons\Drive\Http\Controllers',
        ], function ($router) {
            require __DIR__ . '/Http/routes.php';
        });
    }
}