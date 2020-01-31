<?php

namespace Moharrum\LaravelTranslations\Providers;

use Illuminate\Translation\TranslationServiceProvider;

/**
 * Laravel translations package service provider.
 *
 * @author Khalid Moharrum <khalid.moharram@gmail.com>
 */
class LaravelTranslationsServiceProvider extends TranslationServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('laravel-translations.php'),
        ], 'laravel-translations-config');

        if (! class_exists('CreateTranslationsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . '/../../database/migrations/create_translations_table.php.stub' => database_path('migrations/' . $timestamp . '_create_translations_table.php'),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php',
            'laravel-translations'
        );
    }

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader(): void
    {
        $this->app->singleton('translation.loader', function ($app) {
            $loader = config('laravel-translations.manager');

            return new $loader($app['files'], $app['path.lang']);
        });
    }
}
