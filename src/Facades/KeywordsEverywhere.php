<?php

namespace JeffersonGoncalves\KeywordsEverywhere\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, mixed> keywordData(array<int, string>|string $keywords, ?string $country = 'us', ?string $currency = 'USD', ?string $dataSource = 'gkp')
 * @method static array<string, mixed> relatedKeywords(array<int, string>|string $keywords, ?string $country = 'us', ?string $currency = 'USD', ?string $dataSource = 'gkp')
 * @method static array<string, mixed> pasfKeywords(array<int, string>|string $keywords, ?string $country = 'us', ?string $currency = 'USD', ?string $dataSource = 'gkp')
 * @method static array<string, mixed> domainKeywords(string $domain, ?string $country = 'us', ?string $currency = 'USD')
 * @method static array<string, mixed> domainTraffic(string $domain, ?string $country = 'us')
 * @method static array<string, mixed> domainBacklinks(string $domain)
 * @method static array<string, mixed> domainUniqueBacklinks(string $domain)
 * @method static array<string, mixed> urlKeywords(string $url, ?string $country = 'us', ?string $currency = 'USD')
 * @method static array<string, mixed> urlTraffic(string $url, ?string $country = 'us')
 * @method static array<string, mixed> urlBacklinks(string $url)
 * @method static array<string, mixed> urlUniqueBacklinks(string $url)
 * @method static array<string, mixed> credits()
 * @method static array<string, mixed> countries()
 * @method static array<string, mixed> currencies()
 *
 * @see \JeffersonGoncalves\KeywordsEverywhere\KeywordsEverywhere
 */
class KeywordsEverywhere extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'keywords-everywhere';
    }
}
