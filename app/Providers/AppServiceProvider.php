<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\Entry;
use App\Models\Person;
use App\Models\User;
use App\Observers\AuditLogObserver;
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
        User::observe(AuditLogObserver::class);
        Person::observe(AuditLogObserver::class);
        Car::observe(AuditLogObserver::class);
        Entry::observe(AuditLogObserver::class);
    }
}
