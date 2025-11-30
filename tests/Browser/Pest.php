<?php

/*
|--------------------------------------------------------------------------
| Browser Test Configuration
|--------------------------------------------------------------------------
|
| Configure browser testing behavior for Pest v4 with the browser plugin.
| Tests in this directory will use Playwright for browser automation.
|
*/

use Tests\BrowserTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(BrowserTestCase::class)
    ->use(RefreshDatabase::class)
    ->in(__DIR__);
