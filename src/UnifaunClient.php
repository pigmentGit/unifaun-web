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

    public function performQuery($group, $method, $params = null)
    {
        $rootObject = new \stdClass;
        $rootObject->AuthenticationToken = $this->getAuthToken();
        if($params) {
            foreach($params as $param) {
                $keyName = $param['key'];
                $rootObject->$keyName = $param['value'];
            }
        }

        $this->soapWrapper->add($group, function ($service) {
            $service->wsdl('https://service.apport.net:443/ws/services/ConsignmentWS?wsdl')
                ->trace(true);
        });

        $response = $this->soapWrapper->call($group . '.' . $method, [
            $rootObject
        ]);

        return $response;
    }

    protected function getAuthToken()
    {
        $loginData = new \stdClass();
        $loginData->userName = config('services.unifaun.user_name');
        $loginData->groupName = config('services.unifaun.group_name');
        $loginData->password = config('services.unifaun.password');

        return $loginData;
    }
}
