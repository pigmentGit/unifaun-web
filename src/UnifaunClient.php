<?php

namespace Infab\UnifaunWebTa;

use Illuminate\Support\Collection;
use Artisaninweb\SoapWrapper\SoapWrapper;

class UnifaunClient
{
    protected $soapWrapper;
    protected $config;

    public function __construct(SoapWrapper $soapWrapper, array $unifaunCfg)
    {
        $this->config = $unifaunCfg;
        $this->soapWrapper = $soapWrapper;
    }

    public function performQuery($group, $name)
    {
        $this->soapWrapper->add('ConsignmentResult', function ($service) {
            $service
              ->wsdl('https://service.apport.net:443/ws/services/ConsignmentWS?wsdl')
              ->trace(true);
        });
        $response = $this->soapWrapper->call($group . '.' . $name, [
            $this->getAuthToken()
        ]);

        return $response;
    }

    protected function getAuthToken()
    {
        $loginData = new \stdClass();
        $loginData->userName = 'wsint';
        $loginData->groupName = 'vchain_test';
        $loginData->password = '!ntegration';

        $Login = new \stdClass();
        $Login->AuthenticationToken = $loginData;

        return $Login;
    }

}
