<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\KeywordsEverywhere\Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(fn () => Http::preventStrayRequests())
    ->in('Feature');
