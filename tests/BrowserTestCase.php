<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class BrowserTestCase extends BaseTestCase
{
    /**
     * Create the application instance early to support browser tests.
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        // Pre-create the application so it's available for browser plugin initialization
        if (! $this->app) {
            $this->app = $this->createApplication();
        }
    }

    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require Application::inferBasePath().'/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
