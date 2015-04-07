<?php

namespace sergasd\yandexkassa\tests;

use sergasd\yandexkassa\YandexKassaRequestBase;
use Codeception\Specify;
use Mockery;
use PHPUnit_Framework_TestCase;

class YandexKassaRequestBaseTest extends PHPUnit_Framework_TestCase
{
    use Specify;

    public function testIsValid()
    {
        $examples = [
            [
                'secret',
                [
                    'action' => 'checkOrder',
                    'orderSumAmount' => '87.10',
                    'orderSumCurrencyPaycash' => '643',
                    'orderSumBankPaycash' => '1001',
                    'shopId' => '13',
                    'invoiceId' => '1234567',
                    'customerNumber' => '8123294469',
                    'md5' => '6f58d99969865670ee52bbfd2c03fb90',
                ],
            ],
            [
                's<kY23653f,{9fcnshwq',
                [
                    'action' => 'checkOrder',
                    'orderSumAmount' => '87.10',
                    'orderSumCurrencyPaycash' => '643',
                    'orderSumBankPaycash' => '1001',
                    'shopId' => '13',
                    'invoiceId' => '55',
                    'customerNumber' => '8123294469',
                    'md5' => '1B35ABE38AA54F2931B0C58646FD1321',
                ],
            ],
        ];
        $this->specify('Request must be a valid if md5 is matching', function ($shopPassword, $requestData) {
            $request = $this->createRequest($shopPassword, $requestData);
            verify($request->isValid())->true();
        }, ['examples' => $examples]);
    }

    /**
     * @param $secret
     * @param array $requestData
     * @return YandexKassaRequestBase | \Mockery\MockInterface
     */
    private function createRequest($secret, array $requestData)
    {
        $request = Mockery::mock('sergasd\yandexkassa\YandexKassaRequestBase[]', [$secret]);
        $request->shouldReceive('isValid')->passthru();

        foreach ($requestData as $attribute => $value) {
            $request->$attribute = $value;
        }

        return $request;
    }
}