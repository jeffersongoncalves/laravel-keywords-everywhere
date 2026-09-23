<div class="filament-hidden">

![Laravel Keywords Everywhere](https://raw.githubusercontent.com/jeffersongoncalves/laravel-keywords-everywhere/main/art/jeffersongoncalves-laravel-keywords-everywhere.png)

</div>

# Laravel Keywords Everywhere

[![Tests](https://github.com/jeffersongoncalves/laravel-keywords-everywhere/actions/workflows/tests.yml/badge.svg)](https://github.com/jeffersongoncalves/laravel-keywords-everywhere/actions/workflows/tests.yml)
[![PHPStan](https://github.com/jeffersongoncalves/laravel-keywords-everywhere/actions/workflows/phpstan.yml/badge.svg)](https://github.com/jeffersongoncalves/laravel-keywords-everywhere/actions/workflows/phpstan.yml)
[![Code Style](https://github.com/jeffersongoncalves/laravel-keywords-everywhere/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/jeffersongoncalves/laravel-keywords-everywhere/actions/workflows/fix-php-code-style-issues.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-keywords-everywhere.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-keywords-everywhere)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-keywords-everywhere.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-keywords-everywhere)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-keywords-everywhere.svg?style=flat-square)](LICENSE.md)

A lightweight [Keywords Everywhere API](https://api.keywordseverywhere.com/v1) client for Laravel. It wraps the keyword research, domain/URL, and backlinks endpoints behind a small `KeywordsEverywhere` client/facade, threads your Bearer token, and returns the decoded JSON body — no DTOs, no response wrappers.

## Features

- **Keyword data** — `keywordData()` (search volume, CPC, competition for up to 100 keywords)
- **Related keywords** — `relatedKeywords()`
- **PASF keywords** — `pasfKeywords()` ("People Also Search For")
- **Domain keywords / traffic** — `domainKeywords()` and `domainTraffic()`
- **Domain backlinks** — `domainBacklinks()` and `domainUniqueBacklinks()`
- **URL keywords / traffic** — `urlKeywords()` and `urlTraffic()`
- **URL backlinks** — `urlBacklinks()` and `urlUniqueBacklinks()`
- **Account info** — `credits()`, `countries()`, `currencies()`
- A non-2xx response throws `Illuminate\Http\Client\RequestException`
- Any `keywords` parameter accepts an array or a comma-separated string

## Installation

```bash
composer require jeffersongoncalves/laravel-keywords-everywhere
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="keywords-everywhere-config"
```

## Configuration

Add to your `.env`:

```env
KEYWORDS_EVERYWHERE_API_KEY=your-keywords-everywhere-api-token
```

Generate a token at [https://keywordseverywhere.com/dashboard](https://keywordseverywhere.com/dashboard).

### Config Options

```php
// config/keywords-everywhere.php
return [
    'token' => env('KEYWORDS_EVERYWHERE_API_KEY'),
    'base_url' => env('KEYWORDS_EVERYWHERE_BASE_URL', 'https://api.keywordseverywhere.com/v1'),
];
```

When `keywords-everywhere.token` is null the client falls back to `config('services.keywords-everywhere.token')`.

## Usage

Via the facade:

```php
use JeffersonGoncalves\KeywordsEverywhere\Facades\KeywordsEverywhere;

// Keyword data — array or comma-separated string, max 100 keywords
KeywordsEverywhere::keywordData(['laravel', 'php'], country: 'us', currency: 'USD');
KeywordsEverywhere::keywordData('laravel,php');

KeywordsEverywhere::relatedKeywords('laravel', country: 'us', currency: 'USD');

KeywordsEverywhere::pasfKeywords('laravel', country: 'us', currency: 'USD');

KeywordsEverywhere::domainKeywords('example.com', country: 'us', currency: 'USD');

KeywordsEverywhere::domainTraffic('example.com', country: 'us');

KeywordsEverywhere::domainBacklinks('example.com');

KeywordsEverywhere::domainUniqueBacklinks('example.com');

KeywordsEverywhere::urlKeywords('https://example.com/page', country: 'us', currency: 'USD');

KeywordsEverywhere::urlTraffic('https://example.com/page', country: 'us');

KeywordsEverywhere::urlBacklinks('https://example.com/page');

KeywordsEverywhere::urlUniqueBacklinks('https://example.com/page');

KeywordsEverywhere::credits();

KeywordsEverywhere::countries();

KeywordsEverywhere::currencies();
```

Or inject/resolve the underlying client:

```php
use JeffersonGoncalves\KeywordsEverywhere\KeywordsEverywhere;

$keywordsEverywhere = app(KeywordsEverywhere::class);
$keywordsEverywhere->credits();
```

### Error handling

```php
use Illuminate\Http\Client\RequestException;

try {
    $data = KeywordsEverywhere::keywordData('laravel');
} catch (RequestException $e) {
    // $e->response->status(), $e->response->json(), ...
}
```

## Testing

```bash
composer test
```

## Static Analysis

```bash
composer analyse
```

## Code Formatting

```bash
composer format
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
