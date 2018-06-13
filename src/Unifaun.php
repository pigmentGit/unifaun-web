<?php

namespace Infab\UnifaunWebTa;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

class Unifaun
{
    use Macroable;

    protected $client;

    /**
     * @param \Infab\UnifaunWebTa\UnifaunClient $client
     */
    public function __construct(UnifaunClient $client)
    {
        $this->client = $client;
    }

    public function getConsignmentTemplates(): Collection
    {
        $response = $this->performQuery('ConsignmentResult','getConsignmentTemplates');

        return collect($response);
    }

    public function findByConsignmentNo($id)
    {
        $response = $this->performQuery(
            'ConsignmentResult',
            'findByConsignmentNo',
            [
                ['key' => 'consignmentNo',
                'value' => $id]
            ]
        );

        return collect($response);
    }

    public function findByConsignmentId($id)
    {
        $response = $this->performQuery(
            'ConsignmentResult',
            'findByConsignmentId',
            [
                ['key' => 'consignmentId',
                'value' => $id]
            ]
        );

        return collect($response);
    }

    public function findByPackageId($id)
    {
        $response = $this->performQuery(
            'ConsignmentResult',
            'findByPackageId',
            [
                ['key' => 'packageId',
                'value' => $id]
            ]
        );

        return collect($response);
    }

    public function performQuery($group, $method, $params = null)
    {
        return $this->client->performQuery($group, $method, $params);
    }
}
