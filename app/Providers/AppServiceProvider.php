<?php

namespace App\Providers;

use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $Allpatients = [];
        $Alldoctors = [];
        $Allrooms = [];

        // Check if 'patients' table exists before querying
        if (Schema::hasTable('patients')) {
            $Allpatients = GlobalHelper::getAllPatients();
        }
        // Check if 'doctor' table exists before querying
        if (Schema::hasTable('doctors')) {
            $Alldoctors = GlobalHelper::getAllDoctors();
        }
        // Check if 'rooms' table exists before querying
        if (Schema::hasTable('rooms')) {
            $Allrooms = GlobalHelper::getAllRooms();
        }

        View::share(compact('Allpatients', 'Alldoctors', 'Allrooms'));
    }
}
