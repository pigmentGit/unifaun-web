<?php

namespace Infab\UnifaunWebTa;

use Illuminate\Support\Collection;
use Artisaninweb\SoapWrapper\SoapWrapper;

class UnifaunClient
{
    protected $soapWrapper;
    protected $config;

    public function __construct(SoapWrapper $soapWrapper, array $unifaunCfg = null)
    {
        $this->config = $unifaunCfg;
        $this->soapWrapper = $soapWrapper;
    }

    public function performRequest($group, $method, $params = null)
    {
        $rootObject = new \stdClass();
        $rootObject->AuthenticationToken = $this->getAuthToken();
        if ($params) {
            foreach ($params as $param) {
                if (isset($param[0]['key'])) {
                    foreach ($param as $pop) {
                        $keyName = $pop['key'];
                        $rootObject->$keyName = $pop['value'];
                    }
                } else {
                    $keyName = $param['key'];
                    $rootObject->$keyName = $param['value'];
                }
            }
        }
        $this->soapWrapper->add($group, function ($service) {
            $service->wsdl($this->config['wsdl'] ?? config('services.unifaun.wsdl'))
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
        $loginData->userName = $this->config['user_name'] ?? config('services.unifaun.user_name');
        $loginData->groupName = $this->config['group_name'] ?? config('services.unifaun.group_name');
        $loginData->password = $this->config['password'] ?? config('services.unifaun.password');

        return $loginData;
    }
}
