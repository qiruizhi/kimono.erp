<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
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
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->circular()
                ->locales(['en','zh_TW'])
                ->labels([
                    'en' => 'English (EN)',
                    'zh_TW' => 'Chinese (TW)',
                    // Other custom labels as needed
                ]); // also accepts a closure

        });
    }
}
