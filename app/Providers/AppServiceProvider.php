<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MedicalRecord;
use App\Observers\MedicalRecordObserver;
use App\Services\NFTService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NFTService::class, function ($app) {
            return new NFTService();
        });
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
