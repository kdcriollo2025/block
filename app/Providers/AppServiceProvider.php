<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\MedicalRecordObserver;
use App\Models\MedicalRecord;

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
        MedicalRecord::observe(MedicalRecordObserver::class);
    }
}
