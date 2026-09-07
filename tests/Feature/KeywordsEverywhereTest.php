<?php

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\KeywordsEverywhere\Facades\KeywordsEverywhere as KeywordsEverywhereFacade;
use JeffersonGoncalves\KeywordsEverywhere\KeywordsEverywhere;

it('fetches keyword data from an array of keywords', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_keyword_data' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->keywordData(['laravel', 'php'], 'us', 'USD', 'gkp');

    Http::assertSent(fn (Request $request) => $request->data()['kw'] === ['laravel', 'php']
        && $request->data()['country'] === 'us'
        && $request->data()['currency'] === 'USD'
        && $request->data()['dataSource'] === 'gkp'
        && $request->hasHeader('Authorization', 'Bearer fake-token'));
});

it('fetches keyword data from a csv string', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_keyword_data' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->keywordData('laravel,php');

    Http::assertSent(fn (Request $request) => $request->data()['kw'] === ['laravel', 'php']);
});

it('fetches related keywords', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_related_keywords' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->relatedKeywords('laravel', 'br', 'BRL');

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.keywordseverywhere.com/v1/get_related_keywords'
        && $request->data()['kw'] === ['laravel']
        && $request->data()['country'] === 'br'
        && $request->data()['currency'] === 'BRL');
});

it('fetches pasf keywords', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_pasf_keywords' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->pasfKeywords(['laravel']);

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.keywordseverywhere.com/v1/get_pasf_keywords'
        && $request->data()['kw'] === ['laravel']);
});

it('fetches domain keywords', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_domain_keywords' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->domainKeywords('example.com', 'us', 'USD');

    Http::assertSent(fn (Request $request) => $request->data()['domain'] === 'example.com'
        && $request->data()['country'] === 'us'
        && $request->data()['currency'] === 'USD');
});

it('fetches domain traffic', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_domain_traffic' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->domainTraffic('example.com', 'us');

    Http::assertSent(fn (Request $request) => $request->data()['domain'] === 'example.com'
        && $request->data()['country'] === 'us');
});

it('fetches domain backlinks', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_domain_backlinks' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->domainBacklinks('example.com');

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.keywordseverywhere.com/v1/get_domain_backlinks'
        && $request->data()['domain'] === 'example.com');
});

it('fetches unique domain backlinks', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_unique_domain_backlinks' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->domainUniqueBacklinks('example.com');

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.keywordseverywhere.com/v1/get_unique_domain_backlinks'
        && $request->data()['domain'] === 'example.com');
});

it('fetches url keywords', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_url_keywords' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->urlKeywords('https://example.com/page', 'us', 'USD');

    Http::assertSent(fn (Request $request) => $request->data()['url'] === 'https://example.com/page'
        && $request->data()['country'] === 'us'
        && $request->data()['currency'] === 'USD');
});

it('fetches url traffic', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_url_traffic' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->urlTraffic('https://example.com/page', 'us');

    Http::assertSent(fn (Request $request) => $request->data()['url'] === 'https://example.com/page'
        && $request->data()['country'] === 'us');
});

it('fetches url backlinks', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_page_backlinks' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->urlBacklinks('https://example.com/page');

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.keywordseverywhere.com/v1/get_page_backlinks'
        && $request->data()['url'] === 'https://example.com/page');
});

it('fetches unique url backlinks', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_unique_page_backlinks' => Http::response(['data' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->urlUniqueBacklinks('https://example.com/page');

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.keywordseverywhere.com/v1/get_unique_page_backlinks'
        && $request->data()['url'] === 'https://example.com/page');
});

it('fetches account credits', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_credits' => Http::response(['credits' => 1000], 200),
    ]);

    expect(app(KeywordsEverywhere::class)->credits())->toBe(['credits' => 1000]);

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.keywordseverywhere.com/v1/get_credits'
        && $request->hasHeader('Authorization', 'Bearer fake-token'));
});

it('fetches supported countries', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_countries' => Http::response(['countries' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->countries();

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.keywordseverywhere.com/v1/get_countries');
});

it('fetches supported currencies', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_currencies' => Http::response(['currencies' => []], 200),
    ]);

    app(KeywordsEverywhere::class)->currencies();

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.keywordseverywhere.com/v1/get_currencies');
});

it('throws on a non-2xx response', function () {
    Http::fake([
        'api.keywordseverywhere.com/v1/get_credits' => Http::response(['error' => 'unauthorized'], 401),
    ]);

    expect(fn () => app(KeywordsEverywhere::class)->credits())
        ->toThrow(RequestException::class);
});

it('resolves the facade to the KeywordsEverywhere client', function () {
    expect(KeywordsEverywhereFacade::getFacadeRoot())->toBeInstanceOf(KeywordsEverywhere::class);
});

it('falls back to the services.keywords-everywhere.token config value', function () {
    config()->set('keywords-everywhere.token', null);
    config()->set('services.keywords-everywhere.token', 'services-token');

    Http::fake([
        'api.keywordseverywhere.com/v1/get_credits' => Http::response(['credits' => 1], 200),
    ]);

    app(KeywordsEverywhere::class)->credits();

    Http::assertSent(fn (Request $request) => $request->hasHeader('Authorization', 'Bearer services-token'));
});
