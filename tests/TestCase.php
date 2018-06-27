<?php

namespace Infab\UnifaunWebTa\Test;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();
    }

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

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    // protected function getPackageAliases($app)
    // {
    //     return [
    //         'Unifaun' => \Infab\UnifaunWebTa\UnifaunFacade::class,
    //     ];
    // }
}
