# Laravel Translations

Store your Laravel language files in the database.

## Introduction

This package extends the native [Laravel localization features](https://laravel.com/docs/6.x/localization), adding a `database` translation loader driver along side with the native `translation.loader` driver.

Allowing translation lines to stored in a database, retrieved from the database and stored in cache to avoid accessing the database every time a translation is required, lines can also be updated or deleted from the database providing more flexibility than file based driver.

### Why & acknowledgments

This package is inspired by [spatie/laravel-translation-loader](https://github.com/spatie/laravel-translation-loader). Keep in mind that this package logic and implementation differs from [spatie/laravel-translation-loader](https://github.com/spatie/laravel-translation-loader) and was build to solve `namespacing` issues that [spatie/laravel-translation-loader](https://github.com/spatie/laravel-translation-loader) did not support until the time of this writing.

A main difference that this package does not support more than one `translation_loader` (for now) and primary tested and build for the `database` driver (primarily because translations can be queried and updated easily during runtime when they are stored in a database).

### How this package works?

This package defines a `database` driver that works along side the native file based driver, for example:

```php
    trans('messages.greeting');
```

This will will query both `file` and `database` drivers and will retrieve both translations. By default, the `database` version of the translation will be used unless specified other wise in the package `config/laravel-translations.php` configuration file in `preferred_loader` section. If the `database` driver does not have the specified language line, then the `file` driver is always used as a fallback.

### Installing

Install the package via composer:

```bash
    composer require moharrum/laravel-translations
```

Edit `config/app.php`, remove or comment Laravel translation service provider and add package service provider:
```php
    'providers' => [
        // Illuminate\Translation\TranslationServiceProvider::class,

        Moharrum\LaravelTranslations\Providers\LaravelTranslationsServiceProvider::class,
    ]
```

Finally, you must publish the configuration and migration files:

```php
    php artisan vendor:publish --provider="Moharrum\LaravelTranslations\Providers\LaravelTranslationsServiceProvider"
```

## Usage

We will use our model `Moharrum\LaravelTranslations\Entities\Translation` to store translation lines in the database:

```php
    use Moharrum\LaravelTranslations\Entities\Translation;

    Translation::create([
       'namespace' => '*', // Or simply keep null.
       'group' => 'validation',
       'key' => 'required',
       'text' => [
           'en' => 'This is a required field',
           'ar' => 'الحقل الزامي',
       ],
    ]);
```

The above example works fine when handling translation files stored in `resources/lang` directory, when dealing with namespaced translation files (unpublished package translation files, [module translation files](https://github.com/nWidart/laravel-modules)) we have to specify a `namespace`:

```php
    use Moharrum\LaravelTranslations\Entities\Translation;

    Translation::create([
       'namespace' => 'laravel-ext-validation-rules', // package or module name => translation namespace
       'group' => 'validation',
       'key' => 'max_words',
       'text' => [
           'en' => 'The :attribute must not exceed :num_words word(s)',
           'ar' => 'يجب ان لا يحتوي :attribute على اكثر من :num_words كلمة',
       ],
    ]);
```

In the above examples:

```php
    app()->setLocale('en');

    trans('validation.required');
    // This is a required field

    trans('laravel-ext-validation-rules::validation.max_words');
    // The :attribute must not exceed :num_words word(s)

    app()->setLocale('ar');

    trans('validation.required');
    // الحقل الزامي

    trans('laravel-ext-validation-rules::validation.max_words');
    // يجب ان لا يحتوي :attribute على اكثر من :num_words كلمة
```

## Seeding translation files

Package should be able to load file translations and seed them in a database (TODO).

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
