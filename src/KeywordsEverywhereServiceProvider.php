<?php

namespace JeffersonGoncalves\KeywordsEverywhere;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class KeywordsEverywhereServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('keywords-everywhere')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('keywords-everywhere', fn () => new KeywordsEverywhere);
    }
}
