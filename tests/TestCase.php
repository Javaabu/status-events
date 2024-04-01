<?php

namespace Javaabu\StatusEvents\Tests;

use Javaabu\Activitylog\ActivitylogServiceProvider;
use Javaabu\Helpers\HelpersServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Javaabu\StatusEvents\StatusEventsServiceProvider;

abstract class TestCase extends BaseTestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.key', 'base64:yWa/ByhLC/GUvfToOuaPD7zDwB64qkc/QkaQOrT5IpE=');

        $this->app['config']->set('session.serialization', 'php');

    }

    protected function getPackageProviders($app)
    {
        return [
            HelpersServiceProvider::class,
            StatusEventsServiceProvider::class,
            ActivitylogServiceProvider::class
        ];
    }
}
