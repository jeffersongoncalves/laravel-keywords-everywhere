# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/jeffersongoncalves/laravel-keywords-everywhere/commits/main/compare/v1.0.0...main)

### Added

- Initial release.
- `KeywordsEverywhere` client covering `keywordData()`, `relatedKeywords()`, `pasfKeywords()`, `domainKeywords()`, `domainTraffic()`, `domainBacklinks()`, `domainUniqueBacklinks()`, `urlKeywords()`, `urlTraffic()`, `urlBacklinks()`, `urlUniqueBacklinks()`, `credits()`, `countries()`, and `currencies()`.
- `KeywordsEverywhere` facade backed by a container singleton.
- Configurable token and base URL via `config/keywords-everywhere.php`, with a fallback to `config('services.keywords-everywhere.token')`.

## [v1.0.0](https://github.com/jeffersongoncalves/laravel-keywords-everywhere/commits/main/compare/main...v1.0.0) - 2026-09-07

### Added

- Initial release: lightweight Keywords Everywhere API client for Laravel
- `keywordData()`, `relatedKeywords()`, `pasfKeywords()` — keyword search volume, CPC, competition, related and PASF keywords
- `domainKeywords()`, `domainTraffic()`, `domainBacklinks()`, `domainUniqueBacklinks()` — domain research
- `urlKeywords()`, `urlTraffic()`, `urlBacklinks()`, `urlUniqueBacklinks()` — URL research
- `credits()`, `countries()`, `currencies()` — account endpoints
- Facade, config file, full Pest test suite, PHPStan level 5, Pint
