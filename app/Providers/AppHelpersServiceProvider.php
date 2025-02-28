<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppHelpersServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('Helpers/SettingsHelper.php');
        require_once app_path('Helpers/LoginHelper.php');
        require_once app_path('Helpers/CmsHelper.php');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
