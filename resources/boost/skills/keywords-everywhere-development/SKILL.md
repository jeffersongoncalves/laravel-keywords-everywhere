---
name: keywords-everywhere-development
description: Build and work with the Laravel Keywords Everywhere client - a thin wrapper over the Keywords Everywhere API (keyword research, domain/URL data, and backlinks endpoints)
---

# Keywords Everywhere Development

## When to use this skill

Use this skill when:

- Adding a new Keywords Everywhere API endpoint to the `KeywordsEverywhere` client
- Adjusting how request bodies/queries are built
- Writing tests for Keywords Everywhere API interactions with `Http::fake()`
- Troubleshooting authentication or base URL configuration

## Core Concepts

### The `KeywordsEverywhere` client

`src/KeywordsEverywhere.php` is a single class with one public method per
endpoint. Keyword-accepting methods delegate to a private `post()` helper,
account-info methods delegate to a private `get()` helper. Both:

1. Resolve the base URL and Bearer token from config.
2. Call `$response->throw()` so a non-2xx response raises
   `Illuminate\Http\Client\RequestException` instead of failing silently.
3. Return the decoded JSON body, defaulting to `[]` when the body isn't a
   JSON array.

```php
private function post(string $endpoint, array $body): array
{
    $response = Http::baseUrl($this->baseUrl())
        ->withToken($this->token())
        ->acceptJson()
        ->post($endpoint, $body);

    $response->throw();

    $data = $response->json();

    return is_array($data) ? $data : [];
}
```

### Keywords normalization

Any method accepting `array|string $keywords` (`keywordData()`,
`relatedKeywords()`, `pasfKeywords()`) runs the value through a private
`keywords()` helper that splits a comma-separated string into an array, so
the API always receives a JSON array under the `kw` key:

```php
private function keywords(array|string $keywords): array
{
    return is_array($keywords) ? $keywords : explode(',', $keywords);
}
```

### Facade

`src/Facades/KeywordsEverywhere.php` proxies to a container singleton named
`keywords-everywhere`, registered in
`KeywordsEverywhereServiceProvider::packageRegistered()`. There are no
constructor dependencies, so `app(KeywordsEverywhere::class)` also resolves a
fresh instance without extra bindings.

### Token resolution

```php
private function token(): string
{
    $token = config('keywords-everywhere.token') ?? config('services.keywords-everywhere.token');

    return is_string($token) ? $token : '';
}
```

`config('keywords-everywhere.token')` reads `env('KEYWORDS_EVERYWHERE_API_KEY')`.
When that's null the client falls back to
`config('services.keywords-everywhere.token')`, matching the pattern used
across other jeffersongoncalves API-client packages.

## Adding a New Endpoint

1. Add a public method to `src/KeywordsEverywhere.php` returning
   `array<string, mixed>`, delegating to `post()` or `get()`.
2. Build the body array with keys matching the Keywords Everywhere API
   param names.
3. Add the method to the `@method static` block in
   `src/Facades/KeywordsEverywhere.php`.
4. Add a `Http::fake()` test in
   `tests/Feature/KeywordsEverywhereTest.php` asserting both the endpoint
   path and the request body built from the params.

## Common Patterns

### Success test

```php
it('fetches keyword data from a csv string', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_keyword_data' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->keywordData('laravel,php');

    Http::assertSent(fn (Request $request) => $request->data()['kw'] === ['laravel', 'php']);
});
```

### Error test

```php
it('throws on a non-2xx response', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_credits' => Http::response(['error' => 'unauthorized'], 401),
    ]);

    expect(fn () => app(KeywordsEverywhere::class)->credits())
        ->toThrow(RequestException::class);
});
```

## Troubleshooting

### Error: `kw` in the request body is a string, not an array

**Cause**: A comma-separated string wasn't routed through the private
`keywords()` helper before being sent.

**Solution**: Always pass user input through `keywords()` inside the public
method; never send `$keywords` straight into the body.

### Error: `RequestException` on every call in tests

**Cause**: `Http::preventStrayRequests()` (set in `tests/Pest.php`) blocks any
request that doesn't match a `Http::fake()` pattern.

**Solution**: Make sure the fake pattern matches the full request path, e.g.
`api.keywordseverywhere.com/v1/get_domain_backlinks`.

## API Reference

| Method | Endpoint |
|--------|----------|
| `keywordData(array\|string $keywords, ?string $country, ?string $currency, ?string $dataSource)` | `POST /get_keyword_data` |
| `relatedKeywords(array\|string $keywords, ?string $country, ?string $currency, ?string $dataSource)` | `POST /get_related_keywords` |
| `pasfKeywords(array\|string $keywords, ?string $country, ?string $currency, ?string $dataSource)` | `POST /get_pasf_keywords` |
| `domainKeywords(string $domain, ?string $country, ?string $currency)` | `POST /get_domain_keywords` |
| `domainTraffic(string $domain, ?string $country)` | `POST /get_domain_traffic` |
| `domainBacklinks(string $domain)` | `POST /get_domain_backlinks` |
| `domainUniqueBacklinks(string $domain)` | `POST /get_unique_domain_backlinks` |
| `urlKeywords(string $url, ?string $country, ?string $currency)` | `POST /get_url_keywords` |
| `urlTraffic(string $url, ?string $country)` | `POST /get_url_traffic` |
| `urlBacklinks(string $url)` | `POST /get_page_backlinks` |
| `urlUniqueBacklinks(string $url)` | `POST /get_unique_page_backlinks` |
| `credits()` | `GET /get_credits` |
| `countries()` | `GET /get_countries` |
| `currencies()` | `GET /get_currencies` |

**Returns**: `array<string, mixed>` for every method.

**Throws**: `Illuminate\Http\Client\RequestException` on any non-2xx response.
