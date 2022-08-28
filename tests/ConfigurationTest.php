<?php

namespace Vale\Addons\Drive\Tests;

use Orchestra\Testbench\TestCase;
use Vale\Addons\Drive\DriveProvider;

class ConfigurationTest extends TestCase
{

    /**
     * @test
     * @throws \Exception
     */
    public function validateConfigFile()
    {
        $this->assertArrayHasKey('addons.addon-drive', $this->app['config']);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function validateRoutes()
    {
        $this->assertEquals('/api/move-file', route('api.drive.move-file', [], false));
    }

    protected function getPackageProviders($app)
    {
        return [DriveProvider::class];
    }

}