## Laravel Keywords Everywhere

### Overview

A lightweight [Keywords Everywhere API](https://api.keywordseverywhere.com/v1)
client for Laravel. It wraps the keyword research, domain/URL, and backlinks
endpoints behind a small `KeywordsEverywhere` client/facade, threads your
Bearer token, and returns the decoded JSON body as a plain array — no DTOs,
no response wrapper classes.

### Installation

@verbatim
<code-snippet name="Install the package" lang="bash">
composer require jeffersongoncalves/laravel-keywords-everywhere
</code-snippet>
@endverbatim

### Features

- **Keyword data**: `keywordData(array|string $keywords, ?string $country = 'us', ?string $currency = 'USD', ?string $dataSource = 'gkp')` — up to 100 keywords, array or comma-separated string.
- **Related / PASF keywords**: `relatedKeywords()` and `pasfKeywords()`, same signature as `keywordData()`.
- **Domain data**: `domainKeywords()`, `domainTraffic()`, `domainBacklinks()`, `domainUniqueBacklinks()`.
- **URL data**: `urlKeywords()`, `urlTraffic()`, `urlBacklinks()`, `urlUniqueBacklinks()`.
- **Account info**: `credits()`, `countries()`, `currencies()`.

@verbatim
<code-snippet name="Fetch keyword data and domain backlinks" lang="php">
use JeffersonGoncalves\KeywordsEverywhere\Facades\KeywordsEverywhere;

$data = KeywordsEverywhere::keywordData(['laravel', 'php'], country: 'us');
$backlinks = KeywordsEverywhere::domainBacklinks('example.com');
</code-snippet>
@endverbatim

### Configuration

@verbatim
<code-snippet name="Config example" lang="php">
// config/keywords-everywhere.php
return [
    'token' => env('KEYWORDS_EVERYWHERE_API_KEY'),
    'base_url' => env('KEYWORDS_EVERYWHERE_BASE_URL', 'https://api.keywordseverywhere.com/v1'),
];
</code-snippet>
@endverbatim

### Best Practices

- Always catch `Illuminate\Http\Client\RequestException` around calls — a non-2xx response throws rather than returning an empty array.
- Pass keywords as an array or a comma-separated string; the client always sends them to the API as a JSON array under the `kw` key.
- Prefer the `KeywordsEverywhere` facade in application code; resolve `JeffersonGoncalves\KeywordsEverywhere\KeywordsEverywhere::class` directly only when you need to swap the implementation in tests.
