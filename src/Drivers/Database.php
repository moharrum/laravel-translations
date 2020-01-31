<?php

namespace Moharrum\LaravelTranslations\Drivers;

use Exception;
use Moharrum\LaravelTranslations\Entities\Translation;
use Moharrum\LaravelTranslations\Contracts\TranslationLoaderContract;

/**
 * The database translation driver implementation.
 *
 * @author Khalid Moharrum <khalid.moharram@gmail.com>
 */
class Database implements TranslationLoaderContract
{
    /**
     * Returns all translations for the given locale, group and namespace.
     *
     * @param string $locale
     * @param string $group
     * @param string|null $namespace
     *
     * @return array
     */
    public function load(string $locale, string $group, ?string $namespace = null): array
    {
        $driver = config('laravel-translations.drivers.' . config('laravel-translations.driver'));

        $defaultModel = $driver['entity'];

        $this->isModelValid($defaultModel);

        return $defaultModel::getTranslationsForGroup($locale, $group, $namespace);
    }

    /**
     * Check if the database model implements our model
     * contract or not.
     *
     * @param string $model
     *
     * @throws \Exception
     */
    protected function isModelValid(string $model): void
    {
        $instance = new $model;

        if (! is_a($instance, Translation::class)) {
            throw new Exception('moharrum/laravel-translations says: ' . $model . ' does not extend our ' . Translation::class . ' model.');
        }
    }
}
