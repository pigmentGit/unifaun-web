<?php

namespace Infab\UnifaunWebTa\Test;

use Infab\UnifaunWebTa\Test\TestCase;

class UnifaunWebTaServiceProviderTest extends TestCase
{
    /** @test **/
    public function it_can_get_a_new_client()
    {
        $unifaun = $this->app['unifaun-client'];
        
        $this->assertInstanceOf(\Infab\UnifaunWebTa\Unifaun::class, $unifaun);
    }
}
