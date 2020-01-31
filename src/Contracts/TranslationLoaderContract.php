<?php

namespace Moharrum\LaravelTranslations\Contracts;

/**
 * The contract that all translation loading drivers should obey.
 *
 * @author Khalid Moharrum <khalid.moharram@gmail.com>
 */
interface TranslationLoaderContract
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
    public function load(string $locale, string $group, ?string $namespace = null): array;
}
