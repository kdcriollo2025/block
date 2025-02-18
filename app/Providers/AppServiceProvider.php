<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MedicalRecord;
use App\Observers\MedicalRecordObserver;

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
        if (class_exists(MedicalRecord::class)) {
            MedicalRecord::observe(MedicalRecordObserver::class);
        }
    }
}
