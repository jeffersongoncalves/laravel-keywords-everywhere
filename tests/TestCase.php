<?php

namespace JeffersonGoncalves\KeywordsEverywhere\Tests;

use JeffersonGoncalves\KeywordsEverywhere\KeywordsEverywhereServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            KeywordsEverywhereServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('keywords-everywhere.token', 'fake-token');
    }
}
