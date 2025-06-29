<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
        Blade::component('Dashboard.components.layouts.default', 'layout.default');
        Blade::component('Dashboard.components.layouts.theme-customiser', 'common.theme-customiser');
        Blade::component('Dashboard.components.layouts.sidebar', 'common.sidebar');
        Blade::component('Dashboard.components.layouts.header', 'common.header');
        Blade::component('Dashboard.components.layouts.nav', 'common.nav');
        Blade::component('Dashboard.components.layouts.footer', 'common.footer');
        Blade::component('Dashboard.components.layouts.user-form-field', 'user-form-field');

        Blade::component('Dashboard.components.user-statics.user-stats', 'userStats');

        Blade::directive('ar', function ($expression) {
            return "<?php if(app()->getLocale() === 'ar') echo $expression; ?>";
        });

        Blade::directive('en', function ($expression) {
            return "<?php if(app()->getLocale() === 'en') echo $expression; ?>";
        });

        View::composer('*', function ($view) {

            $userData = getAdminUserFromToken();
            $statsForServiceProviders = generateUserStats(1);
            $statsForServiceRequesters = generateUserStats(2);

            $view->with('userData', $userData);
            $view->with('statsForServiceProviders', $statsForServiceProviders);
            $view->with('statsForServiceRequesters', $statsForServiceRequesters);

        });
    }
}
