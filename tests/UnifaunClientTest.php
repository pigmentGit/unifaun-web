<?php

namespace Infab\UnifaunWebTa\Test;

use Mockery;
use Illuminate\Support\Collection;
use Infab\UnifaunWebTa\Test\TestCase;
use Infab\UnifaunWebTa\UnifaunClient;
use Infab\UnifaunWebTa\Unifaun;

class UnifaunClientTest extends TestCase
{
    protected $unifaunClient;
    protected $unifaun;

    public function setUp()
    {
        $this->unifaunClient = Mockery::mock(UnifaunClient::class);
        $this->unifaun = new Unifaun($this->unifaunClient);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /** @test **/
    public function it_can_fetch_consignment_templates()
    {
        $this->unifaunClient
            ->shouldReceive('performQuery')
            ->once()
            ->andReturn([
                'result' => [
                    'consignmentTemplate' => [
                        'name' => 'vChain',
                        'description' => ''
                    ]
                ]
            ]);
        
        $response = $this->unifaun->getConsignmentTemplates();
        
        // Assert
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals('vChain', $response->first()['consignmentTemplate']['name']);
    
    }
}
