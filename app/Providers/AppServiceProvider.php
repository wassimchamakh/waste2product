<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\NotificationComposer;
use App\View\Composers\FrontOfficeNotificationComposer;

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
        // Share notifications with BackOffice navbar
        View::composer('BackOffice.layouts.navbar', NotificationComposer::class);
        
        // Share notifications with FrontOffice navbar
        View::composer('FrontOffice.layout1.navbar', FrontOfficeNotificationComposer::class);
    }
}
