<?php

namespace PerfectOblivion\Valid\Sanitizer\Laravel;

use Illuminate\Support\ServiceProvider;

/**
 * File copied from Waavi/Sanitizer https://github.com/waavi/sanitizer
 * Sanitization functionality to be customized within this project before a 1.0 release.
 */
class SanitizerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('sanitizer', function ($app) {
            return new Factory;
        });
    }
}
