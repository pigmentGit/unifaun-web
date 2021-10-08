<?php

namespace Infab\UnifaunWebTa;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

class Unifaun
{
    use Macroable;

    protected $client;

    protected $bookingData;

    protected $goods = [];

    protected $consignmentParts = [];

    protected $transportProduct;

    protected $transportServices = [];


    /**
     * @param \Infab\UnifaunWebTa\UnifaunClient $client
     */
    public function __construct(UnifaunClient $client)
    {
        $this->client = $client;
    }

    public function get()
    {
        return [
            'bookingData' => $this->bookingData,
            'goods' => $this->goods,
            'consignmentParts' => $this->consignmentParts,
            'transportProduct' => $this->transportProduct,
            // 'transportServices' => $this->transportServices
        ];
    }

    public function prepareBooking(array $data)
    {
        $this->bookingData = $data;

        return $this->bookingData;
    }

    public function addGoods(array $data)
    {
        array_push($this->goods, $data);

        return $this->goods;
    }

    public function addConsignmentPart(array $data)
    {
        array_push($this->consignmentParts, $data);

        return $this;
    }

    public function getConsignmentParts()
    {
        return $this->consignmentParts;
    }

    public function addTransportProduct(array $data)
    {
        $this->transportProduct = $data;

        return $this;
    }

    public function getConsignmentTemplates(): Collection
    {
        $response = $this->performRequest('ConsignmentResult', 'getConsignmentTemplates');

        return collect($response);
    }

    public function findByConsignmentNo($id)
    {
        $response = $this->performRequest(
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
        $response = $this->performRequest(
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
        $response = $this->performRequest(
            'ConsignmentResult',
            'findByPackageId',
            [
                ['key' => 'packageId',
                'value' => $id]
            ]
        );

        return collect($response);
    }

    public function makeBooking()
    {
        $bookingItem = new \stdClass();
        // $goodsItems = $this->getGoods();

        $bookingItem->templateName = 'Package';
        $bookingItem->orderNo = $this->bookingData['orderNo'];
        $bookingItem->GoodsItem = $this->getGoods();
        $bookingItem->Part = $this->getParts();
        $bookingItem->TransportProduct = $this->getTransportProduct();
        $response = $this->performRequest(
            'ConsignmentResult',
            'book',
            [
                [
                    'key' => 'Consignment',
                    'value' => $bookingItem
                ],
                [
                    'key' => 'transactionId',
                    'value' => $this->bookingData['transactionId']
                ]
            ]
        );

        return collect($response);
    }

    public function getGoods(): array
    {
        $goods = [];
        foreach ($this->goods as $item) {
            $xml  = "<GoodsItem xmlns:v1=\"http://www.spedpoint.com/consignment/types/v1_0\">";
            $xml .= "<v1:noOfPackages>{$item['packages']}</v1:noOfPackages>";
            $xml .= "<v1:weight>{$item['weight']}</v1:weight>";
            $xml .= "</GoodsItem>";
            $item = new \SoapVar($xml, XSD_ANYXML);
            array_push($goods, $item);
        }

        return $goods;
    }


    public function getParts(): array
    {
        $parts = [];
        foreach ($this->consignmentParts as $item) {
            $part = new \stdClass();
            $part->role = $item['role'];
            $part->Address = $this->getAddress($item);
            array_push($parts, $part);
        }

        return $parts;
    }

    protected function getAddress($part)
    {
        $random = str_random(8);
        $xml  = "<Address xmlns:v1=\"http://www.spedpoint.com/consignment/types/v1_0\">";
        $xml .= "<v1:id>{$part['id']}</v1:id>";
        $xml .= "<v1:name>{$part['name']}</v1:name>";
        $xml .= "<v1:address>{$part['address']}</v1:address>";
        $xml .= "<v1:postcode>{$part['postcode']}</v1:postcode>";
        $xml .= "<v1:city>{$part['city']}</v1:city>";
        $xml .= "<v1:countrycode>{$part['country_code']}</v1:countrycode>";
        $xml .= "</Address>";
        if (isset($part['communication'])) {
            $xml .= "<Communication xmlns:v1=\"http://www.spedpoint.com/consignment/types/v1_0\">";
            $xml .= "<v1:contactPerson>{$part['communication']['contact_person']}</v1:contactPerson>";
            $xml .= "<v1:phone>{$part['communication']['phone']}</v1:phone>";
            $xml .= "<email notify='false'>{$part['communication']['email']}</email>";
            $xml .= "</Communication>";
        }

        $address  = new \SoapVar($xml, XSD_ANYXML);

        return $address;
    }

    public function getTransportProduct()
    {
        $transportProduct = new \stdClass();
        $transportValue = new \stdClass();
        $addService = new \stdClass();
        $advice = new \stdClass();
        $advice->value = true;
        // $addService->value = "vchainnormal";
        $transportValue->value = "P";
        // dd($transportValue);
        $transportProduct->PaymentInstruction = $transportValue;
        $transportProduct->advice = $advice;
        // $transportProduct->AddService = $addService;

        return $transportProduct;
    }

    public function performRequest($group, $method, $params = null)
    {
        return $this->client->performRequest($group, $method, $params);
    }
}
