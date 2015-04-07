<?php

namespace sergasd\yandexkassa\tests;

use sergasd\yandexkassa\CheckOrderRequest;
use sergasd\yandexkassa\PaymentAvisoRequest;
use sergasd\yandexkassa\ResponseFactory;
use sergasd\yandexkassa\YandexKassa;
use Codeception\Specify;
use Mockery;
use PHPUnit_Framework_TestCase;

class ResponseFactoryTest extends PHPUnit_Framework_TestCase
{
    use Specify;

    /** @var  YandexKassa */
    private $yandexKassa;
    /** @var  ResponseFactory */
    private $responseFactory;

    protected function setUp()
    {
        parent::setUp();
        $this->yandexKassa = Mockery::mock('sergasd\yandexkassa\YandexKassa');
        $this->responseFactory = new ResponseFactory($this->yandexKassa);
    }

    public function testCreateCheckOrderResponse()
    {
        $examples = [
            ['2011-05-04T20:38:00.000+04:00', null, '2011-05-04T20:38:00.000+04:00'],
            ['2011-05-04T20:38:00.000+04:00', 'custom date', 'custom date'],
        ];
        $this->specify('performedDatetime will created from request requestDatetime or custom', function ($requestDatetime, $performedDatetime, $expected) {
            $checkOrderRequest = new CheckOrderRequest('test');
            $checkOrderRequest->requestDatetime = $requestDatetime;

            $response = $this->responseFactory->createCheckOrderResponse($checkOrderRequest, $performedDatetime);
            $this->assertEquals('checkOrderResponse', $this->readAttribute($response, 'tagName'));
            $this->assertEquals($expected, $response->performedDatetime);

        }, ['examples' => $examples]);
    }

    public function testCreatePaymentAvisoResponse()
    {
        $examples = [
            ['2011-05-04T20:38:00.000+04:00', null, '2011-05-04T20:38:00.000+04:00'],
            ['2011-05-04T20:38:00.000+04:00', 'custom date', 'custom date'],
        ];
        $this->specify('performedDatetime will created from request requestDatetime or custom', function ($requestDatetime, $performedDatetime, $expected) {
            $checkOrderRequest = new PaymentAvisoRequest('test');
            $checkOrderRequest->requestDatetime = $requestDatetime;

            $response = $this->responseFactory->createPaymentAvisoResponse($checkOrderRequest, $performedDatetime);
            $this->assertEquals('paymentAvisoResponse', $this->readAttribute($response, 'tagName'));
            $this->assertEquals($expected, $response->performedDatetime);

        }, ['examples' => $examples]);
    }
}