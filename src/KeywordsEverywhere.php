<?php

namespace JeffersonGoncalves\KeywordsEverywhere;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * Thin client over the Keywords Everywhere API v1
 * (https://api.keywordseverywhere.com/v1). Every method maps to a single
 * endpoint and returns the decoded JSON body as an array. A non-2xx response
 * throws Illuminate\Http\Client\RequestException via Http::throw().
 */
class KeywordsEverywhere
{
    /**
     * Search volume/CPC/competition data for up to 100 keywords.
     *
     * @param  array<int, string>|string  $keywords
     * @return array<string, mixed>
     */
    public function keywordData(array|string $keywords, ?string $country = 'us', ?string $currency = 'USD', ?string $dataSource = 'gkp'): array
    {
        return $this->post('/get_keyword_data', [
            'country' => $country,
            'currency' => $currency,
            'dataSource' => $dataSource,
            'kw' => $this->keywords($keywords),
        ]);
    }

    /**
     * Related keyword suggestions for one or more seed keywords.
     *
     * @param  array<int, string>|string  $keywords
     * @return array<string, mixed>
     */
    public function relatedKeywords(array|string $keywords, ?string $country = 'us', ?string $currency = 'USD', ?string $dataSource = 'gkp'): array
    {
        return $this->post('/get_related_keywords', [
            'country' => $country,
            'currency' => $currency,
            'dataSource' => $dataSource,
            'kw' => $this->keywords($keywords),
        ]);
    }

    /**
     * "People Also Search For" keyword suggestions for one or more seed keywords.
     *
     * @param  array<int, string>|string  $keywords
     * @return array<string, mixed>
     */
    public function pasfKeywords(array|string $keywords, ?string $country = 'us', ?string $currency = 'USD', ?string $dataSource = 'gkp'): array
    {
        return $this->post('/get_pasf_keywords', [
            'country' => $country,
            'currency' => $currency,
            'dataSource' => $dataSource,
            'kw' => $this->keywords($keywords),
        ]);
    }

    /**
     * Keywords a domain ranks for.
     *
     * @return array<string, mixed>
     */
    public function domainKeywords(string $domain, ?string $country = 'us', ?string $currency = 'USD'): array
    {
        return $this->post('/get_domain_keywords', [
            'country' => $country,
            'currency' => $currency,
            'domain' => $domain,
        ]);
    }

    /**
     * Estimated organic traffic for a domain.
     *
     * @return array<string, mixed>
     */
    public function domainTraffic(string $domain, ?string $country = 'us'): array
    {
        return $this->post('/get_domain_traffic', [
            'country' => $country,
            'domain' => $domain,
        ]);
    }

    /**
     * Backlinks pointing to a domain.
     *
     * @return array<string, mixed>
     */
    public function domainBacklinks(string $domain): array
    {
        return $this->post('/get_domain_backlinks', [
            'domain' => $domain,
        ]);
    }

    /**
     * Unique (deduplicated by referring domain) backlinks pointing to a domain.
     *
     * @return array<string, mixed>
     */
    public function domainUniqueBacklinks(string $domain): array
    {
        return $this->post('/get_unique_domain_backlinks', [
            'domain' => $domain,
        ]);
    }

    /**
     * Keywords a URL ranks for.
     *
     * @return array<string, mixed>
     */
    public function urlKeywords(string $url, ?string $country = 'us', ?string $currency = 'USD'): array
    {
        return $this->post('/get_url_keywords', [
            'country' => $country,
            'currency' => $currency,
            'url' => $url,
        ]);
    }

    /**
     * Estimated organic traffic for a URL.
     *
     * @return array<string, mixed>
     */
    public function urlTraffic(string $url, ?string $country = 'us'): array
    {
        return $this->post('/get_url_traffic', [
            'country' => $country,
            'url' => $url,
        ]);
    }

    /**
     * Backlinks pointing to a URL.
     *
     * @return array<string, mixed>
     */
    public function urlBacklinks(string $url): array
    {
        return $this->post('/get_page_backlinks', [
            'url' => $url,
        ]);
    }

    /**
     * Unique (deduplicated by referring domain) backlinks pointing to a URL.
     *
     * @return array<string, mixed>
     */
    public function urlUniqueBacklinks(string $url): array
    {
        return $this->post('/get_unique_page_backlinks', [
            'url' => $url,
        ]);
    }

    /**
     * Remaining API credits for the account.
     *
     * @return array<string, mixed>
     */
    public function credits(): array
    {
        return $this->get('/get_credits');
    }

    /**
     * Countries supported by the API.
     *
     * @return array<string, mixed>
     */
    public function countries(): array
    {
        return $this->get('/get_countries');
    }

    /**
     * Currencies supported by the API.
     *
     * @return array<string, mixed>
     */
    public function currencies(): array
    {
        return $this->get('/get_currencies');
    }

    /**
     * @param  array<int, string>|string  $keywords
     * @return array<int, string>
     */
    private function keywords(array|string $keywords): array
    {
        return is_array($keywords) ? $keywords : explode(',', $keywords);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
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

    /**
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    private function get(string $endpoint): array
    {
        $response = Http::baseUrl($this->baseUrl())
            ->withToken($this->token())
            ->acceptJson()
            ->get($endpoint);

        $response->throw();

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    private function token(): string
    {
        $token = config('keywords-everywhere.token') ?? config('services.keywords-everywhere.token');

        return is_string($token) ? $token : '';
    }

    private function baseUrl(): string
    {
        $baseUrl = config('keywords-everywhere.base_url', 'https://api.keywordseverywhere.com/v1');

        return is_string($baseUrl) && $baseUrl !== '' ? $baseUrl : 'https://api.keywordseverywhere.com/v1';
    }
}
