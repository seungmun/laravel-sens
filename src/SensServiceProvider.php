<?php

namespace Seungmun\Sens;

use Seungmun\Sens\AlimTalk\AlimTalk;
use Seungmun\Sens\AlimTalk\AlimTalkChannel;
use Seungmun\Sens\Sms\Sms;
use Seungmun\Sens\Sms\SmsChannel;
use Illuminate\Support\ServiceProvider;

class SensServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-sens.php',
            'laravel-sens'
        );

        // Register SENS SMS service.
        $this->app->when(SmsChannel::class)
            ->needs(Sms::class)
            ->give(function ($app) {
                return new Sms($app['config']->get('laravel-sens'));
            });

        // Register SENS AlimTalk service.
        $this->app->when(AlimTalkChannel::class)
            ->needs(AlimTalk::class)
            ->give(function ($app) {
                return new AlimTalk($app['config']->get('laravel-sens'));
            });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-sens.php' => config_path('laravel-sens.php')
        ], 'config');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
