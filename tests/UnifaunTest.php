<?php

namespace Infab\UnifaunWebTa\Test;

use Mockery;
use Illuminate\Support\Collection;
use Infab\UnifaunWebTa\Test\TestCase;
use Infab\UnifaunWebTa\UnifaunClient;
use Infab\UnifaunWebTa\Unifaun;

class UnifaunTest extends TestCase
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
            ->shouldReceive('performRequest')
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

    /** @test **/
    public function it_can_find_a_consignment_by_no()
    {
        $expectedArguments = [
            'ConsignmentResult',
            'findByConsignmentNo',
            [['key' => 'consignmentNo', 'value' => '123']]
        ];

        $this->unifaunClient
            ->shouldReceive('performRequest')->withArgs($expectedArguments)
            ->once()
            ->andReturn([
                'result' => [
                    'consignments' => [
                        'Part' => [
                            0 => [
                                'Address' => [
                                    'id' => '181818',
                                    'name' => 'Infab'
                                ],
                                'Communication' => [
                                    'contactPerson' => 'Albin N',
                                    'phone' => '0733228083'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        
        $response = $this->unifaun->findByConsignmentNo('123');
        
        // Assert
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals('Infab', $response->first()['consignments']['Part'][0]['Address']['name']);
    }

    /** @test **/
    public function it_can_get_a_consignment_by_id()
    {
        // Arrange
        
        $expectedArguments = [
            'ConsignmentResult',
            'findByConsignmentId',
            [['key' => 'consignmentId', 'value' => '1528808552694f191f478']]
        ];

        $this->unifaunClient
            ->shouldReceive('performRequest')->withArgs($expectedArguments)
            ->once()
            ->andReturn([
                'result' => [
                    'consignments' => [
                        'Part' => [
                            0 => [
                                'Address' => [
                                    'id' => '181818',
                                    'name' => 'Infab'
                                ],
                                'Communication' => [
                                    'contactPerson' => 'Albin N',
                                    'phone' => '0733228083'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        
        $response = $this->unifaun->findByConsignmentId('1528808552694f191f478');
        
        // Assert
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals('Infab', $response->first()['consignments']['Part'][0]['Address']['name']);
    }

    /** @test **/
    public function it_can_find_a_package_via_package_id()
    {
        $expectedArguments = [
            'ConsignmentResult',
            'findByPackageId',
            [['key' => 'packageId', 'value' => '373323997883182561']]
        ];
        $this->unifaunClient
            ->shouldReceive('performRequest')->withArgs($expectedArguments)
            ->once()
            ->andReturn([
                'result' => [
                    'consignments' => [
                        'Part' => [
                            0 => [
                                'Address' => [
                                    'id' => '181818',
                                    'name' => 'Infab'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    
        $response = $this->unifaun->findByPackageId('373323997883182561');
        
        // Assert
        $this->assertInstanceOf(Collection::class, $response);
    }

    /** @test **/
    // public function it_can_book_a_shipment()
    // {
    //     // Arrange
    //     $expectedArguments = [
    //         'ConsignmentResult',
    //         'findByPackageId',
    //         [['key' => 'packageId', 'value' => '373323997883182561']]
    //     ];
    //     $this->unifaunClient
    //         ->shouldReceive('performRequest')->withArgs($expectedArguments)
    //         ->once()
    //         ->andReturn([
    //             'result' => [
    //                 'consignments' => [
    //                     'Part' => [
    //                         0 => [
    //                             'Address' => [
    //                                 'id' => '181818',
    //                                 'name' => 'Infab'
    //                             ]
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]);
    
    //     $response = $this->unifaun->makeBooking('373323997883182561');
        
    //     // Assert
    //     $this->assertInstanceOf(Collection::class, $response);
    // }

    /** @test **/
    public function it_can_prepare_booking_data()
    {
        $expectedArguments = [
            'templateName' => 'vchain',
            'orderNo' => '123',
            'transactionId' => 'tr_id123'
        ];
        $bookingData = $this->unifaun->prepareBooking($expectedArguments);
        
        // Assert
        $this->assertEquals($bookingData, $expectedArguments);
    }

    /** @test **/
    public function it_can_add_goods()
    {
        $expectedArguments0 = [
            'weight' => 9,
            'weightUnit' => 'kg',
            'packages' => 1
        ];
        $expectedArguments1 = [
            'weight' => 12.9,
            'weightUnit' => 'kg',
            'packages' => 2
        ];

        $bookingData = $this->unifaun->addGoods($expectedArguments0);
        $bookingData = $this->unifaun->addGoods($expectedArguments1);

        $this->assertEquals($bookingData, [$expectedArguments0, $expectedArguments1]);
        $this->assertCount(2, $bookingData);
    }

    /** @test **/
    public function it_can_add_parts()
    {
        // Arrange
        $expectedArguments0 = [
            'role' => 'consignor',
            'name' => 'Infab',
            'address' => 'Vägen 19',
            'postcode' => '291 65',
            'city' => 'Kristianstad',
            'country_code' => 'SE'
        ];
        $expectedArguments1 = [
            'role' => 'consignee',
            'name' => 'Danish guy',
            'address' => 'Stret 19',
            'postcode' => '291 65',
            'city' => 'Kolding',
            'country_code' => 'DK'
        ];
        
        // Act
        $bookingData = $this->unifaun->addConsignmentPart($expectedArguments0)
            ->addConsignmentPart($expectedArguments1)
            ->getConsignmentParts();
        
        // $bookingData = $this->unifaun->getConsignmentParts();
        //Assert
        $this->assertCount(2, $this->unifaun->getConsignmentParts());
        $this->assertEquals($bookingData, [$expectedArguments0, $expectedArguments1]);
    }

    /** @test **/
    public function it_can_add_a_transport_product()
    {
        // Arrange
        $expectedArguments = [
            'payment_instruction' => 'P',
            'advice' => true,
        ];

        // Act
        $data = $this->unifaun->addTransportProduct($expectedArguments)->get();
    
        // Assert
        $this->assertEquals($expectedArguments, $data['transportProduct']);
    }

    /** @test **/
    public function it_can_make_a_booking()
    {

        $this->unifaun->prepareBooking([
            'templateName' => 'vchain',
            'orderNo' => str_random(8),
            'transactionId' => 'tr_id_' . str_random(14)
        ]);

        $this->unifaun->addGoods([
            'weight' => 12.3,
            'weightUnit' => 'kg',
            'packages' => 4
        ]);

        $this->unifaun->addGoods([
            'weight' => 1.1,
            'weightUnit' => 'kg',
            'packages' => 1
        ]);

        $this->unifaun->addConsignmentPart([
            'role' => 'consignor',
            'id' => 12,
            'name' => 'Albin Nilsson',
            'address' => 'Vägen 19',
            'postcode' => '291 65',
            'city' => 'Kristianstad',
            'country_code' => 'SE',
        ]);

        $this->unifaun->addConsignmentPart([
            'role' => 'consignee',
            'id' => 321,
            'name' => 'Mikkel Madsen',
            'address' => 'Gate 19',
            'postcode' => '31399',
            'city' => 'Odense',
            'country_code' => 'DK',
        ]);

        $this->unifaun->addTransportProduct([
            'payment_instruction' => 'P',
            'advice' => true,
        ]);
        $data = $this->unifaun->get();
        ///
        $bookingItem = new \stdClass;
        // $goodsItems = $this->getGoods();

        $bookingItem->templateName = 'vchain';
        $bookingItem->orderNo = $data['bookingData']['orderNo'];
        $bookingItem->GoodsItem = $this->unifaun->getGoods();
        $bookingItem->Part = $this->unifaun->getParts();
        $bookingItem->TransportProduct = $this->unifaun->getTransportProduct();

        // Arrange
        $expectedArguments = [
            'ConsignmentResult',
            'book',
            [
                [
                    'key' => 'Consignment',
                    'value' => $bookingItem
                ],
                [
                    'key' => 'transactionId',
                    'value' => $data['bookingData']['transactionId']
                ]
            ]
        ];
        $this->unifaunClient
            ->shouldReceive('performRequest')->withArgs($expectedArguments)
            ->once()
            ->andReturn([
                'result' => [
                    'status' => 1
                ]
            ]);
    
        $response = $this->unifaun->makeBooking();
        
        // Assert
        $this->assertInstanceOf(Collection::class, $response);
    }
}
