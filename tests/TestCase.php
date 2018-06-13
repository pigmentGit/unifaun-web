<?php

namespace Infab\UnifaunWebTa\Test;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Infab\UnifaunWebTa\UnifaunWebTaServiceProvider::class,
        ];
    }
}
