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

    public function performQuery($group, $name)
    {
        return $this->client->performQuery($group, $name);
    }
}
