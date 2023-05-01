<?php

namespace Infab\UnifaunWebTa;

use Illuminate\Support\ServiceProvider;
use Artisaninweb\SoapWrapper\SoapWrapper;

class UnifaunWebTaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/services.php', 'services');

        $this->app->bind(UnifaunClient::class, function () {
            $unifaunConfig = config('services.unifaun');
            return new UnifaunClient(new SoapWrapper, $unifaunConfig);
        });

        $this->app->bind(Unifaun::class, function () {
            $unifaunConfig = config('services.unifaun');
            $client = app(UnifaunClient::class);

            return new Unifaun($client);
        });

        $this->app->alias(Unifaun::class, 'unifaun-client');
    }
}
