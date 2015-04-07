<?php

namespace sergasd\yandexkassa\tests;

use Mockery\MockInterface;
use sergasd\yandexkassa\CheckOrderRequest;
use sergasd\yandexkassa\IRequestHandler;
use sergasd\yandexkassa\PaymentAvisoRequest;
use sergasd\yandexkassa\RequestFactory;
use sergasd\yandexkassa\YandexKassa;
use sergasd\yandexkassa\YandexKassaResponse;
use PHPUnit_Framework_TestCase;
use Codeception\Specify;
use Mockery;

class YandexKassaTest extends PHPUnit_Framework_TestCase
{
    use Specify;

    private $requestData;

    private $responseData;

    private $shopId = 'my shop id';

    private $invoiceId = ' my invoice id';

    private $performedDatetime = '2011-05-04T20:38:00.000+04:00';

    private $shopPassword = 'my password';

    /** @var  YandexKassa */
    private $kassa;

    /** @var RequestFactory | MockInterface */
    private $requestFactory;

    /** @var CheckOrderRequest | MockInterface */
    private $checkOrderRequest;

    /** @var PaymentAvisoRequest | MockInterface */
    private $paymentAvisoRequest;

    /** @var IRequestHandler | MockInterface */
    private $requestHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->beforeSpecify(function () {
            $this->checkOrderRequest = Mockery::mock('sergasd\yandexkassa\CheckOrderRequest');
            $this->paymentAvisoRequest = Mockery::mock('sergasd\yandexkassa\PaymentAvisoRequest');
            $this->requestFactory = Mockery::mock('sergasd\yandexkassa\RequestFactory');
            $this->requestFactory->shouldReceive('createCheckOrderRequest')->andReturn($this->checkOrderRequest);
            $this->requestFactory->shouldReceive('createPaymentAvisoRequest')->andReturn($this->paymentAvisoRequest);
            $this->requestHandler = Mockery::mock('sergasd\yandexkassa\IRequestHandler');

            $this->kassa = new YandexKassa($this->requestHandler, $this->requestFactory);
            $this->kassa->shopId = $this->shopId;
            $this->kassa->shopPassword = $this->shopPassword;
        });
    }

    public function testCheckOrder()
    {
        $this->requestData = [
            'requestDatetime' => $this->performedDatetime,
            'action' => 'checkOrder',
            'md5' => '8256D2A032A35709EAF156270C9EFE2E',
            'shopId' => $this->shopId,
            'shopArticleId' => '456',
            'invoiceId' => $this->invoiceId,
            'customerNumber' => '8123294469',
            'orderCreatedDatetime' => '2011-05-04T20:38:00.000+04:00',
            'orderSumAmount' => '87.10',
            'orderSumCurrencyPaycash' => '643',
            'orderSumBankPaycash' => '1001',
            'shopSumAmount' => '86.23',
            'shopSumCurrencyPaycash' => '643',
            'shopSumBankPaycash' => '1001',
            'paymentPayerCode' => '42007148320',
            'paymentType' => 'AC',
            'MyField' => 'My value',
        ];

        $this->responseData = [
            'performedDatetime' => $this->performedDatetime,
            'invoiceId' => $this->invoiceId,
            'shopId' => $this->shopId,
        ];

        $this->specify('It should return OK when request is valid AND callback return true', function () {
            $this->expectsCheckOrderCallbackReturn(true);
            $this->fillCheckOrderRequest($this->requestData);
            $this->expectsCheckRequestIsValid();

            $xml = $this->makeCheckOrderResponseXml([
                'code' => YandexKassaResponse::STATUS_SUCCESS,
            ]);

            $this->assertXmlStringEqualsXmlString($xml, $this->kassa->createCheckOrderResponse($this->requestData)->getContent());
        });

        $this->specify('It should return with error when request is invalid', function () {
            $this->expectsCheckOrderCallbackReturn(true);
            $this->fillCheckOrderRequest($this->requestData);
            $this->expectsCheckRequestIsInvalid();

            $xml = $this->makeCheckOrderResponseXml([
                'code' => YandexKassaResponse::STATUS_AUTHORIZE_ERROR,
                'message' => YandexKassaResponse::MESSAGE_INVALID_REQUEST,
            ]);

            $this->assertXmlStringEqualsXmlString($xml, $this->kassa->createCheckOrderResponse($this->requestData)->getContent());
        });

        $this->specify('It should return error when order check callback return false', function () {
            $this->expectsCheckOrderCallbackReturn(false);
            $this->fillCheckOrderRequest($this->requestData);
            $this->expectsCheckRequestIsValid();

            $xml = $this->makeCheckOrderResponseXml([
                'code' => YandexKassaResponse::STATUS_REFUSE_ACCEPT_TRANSFER,
                'message' => YandexKassaResponse::MESSAGE_REFUSE_ACCEPT_TRANSFER,
            ]);

            $this->assertXmlStringEqualsXmlString($xml, $this->kassa->createCheckOrderResponse($this->requestData)->getContent());
        });
    }


    public function testPaymentAviso()
    {
        $this->requestData = [
            'requestDatetime' => '2011-05-04T20:38:00.000+04:00',
            'action' => 'paymentAviso',
            'md5' => '45125C95A20A7F25B63D58EA304AFED2',
            'shopId' => $this->shopId,
            'shopArticleId' => '456',
            'invoiceId' => $this->invoiceId,
            'customerNumber' => '8123294469',
            'orderCreatedDatetime' => '2011-05-04T20:38:00.000+04:00',
            'orderSumAmount' => '87.10',
            'orderSumCurrencyPaycash' => '643',
            'orderSumBankPaycash' => '1001',
            'shopSumAmount' => '86.23',
            'shopSumCurrencyPaycash' => '643',
            'shopSumBankPaycash' => '1001',
            'paymentDatetime' => '2011-05-04T20:38:10.000+04:00',
            'paymentPayerCode' => '42007148320',
            'paymentType' => 'AC',
            'cps_user_country_code' => 'RU',
            'MyField' => 'My value',
        ];

        $this->responseData = [
            'performedDatetime' => $this->performedDatetime,
            'invoiceId' => $this->invoiceId,
            'shopId' => $this->shopId,
        ];

        $this->specify('It should return OK when request is valid AND callback return true', function () {
            $this->fillPaymentAvisoRequest($this->requestData);
            $this->expectsPaymentAvisoRequestIsValid();
            $this->expectsPaymentAvisoCallbackReturn(true);

            $xml = $this->makePaymentAvisoResponseXml([
                'code' => YandexKassaResponse::STATUS_SUCCESS,
            ]);

            $this->assertXmlStringEqualsXmlString($xml, $this->kassa->createPaymentAvisoResponse($this->requestData)->getContent());
        });

        $this->specify('It should return with error when request is invalid', function () {
            $this->fillPaymentAvisoRequest($this->requestData);
            $this->expectsPaymentAvisoCallbackReturn(true);
            $this->expectsPaymentAvisoRequestIsInvalid();

            $xml = $this->makePaymentAvisoResponseXml([
                'code' => YandexKassaResponse::STATUS_AUTHORIZE_ERROR,
                'message' => YandexKassaResponse::MESSAGE_INVALID_REQUEST,
            ]);

            $this->assertXmlStringEqualsXmlString($xml, $this->kassa->createPaymentAvisoResponse($this->requestData)->getContent());
        });

        $this->specify('It should return empty response when order check callback return false', function () {
            $this->fillPaymentAvisoRequest($this->requestData);
            $this->expectsPaymentAvisoCallbackReturn(false);
            $this->expectsPaymentAvisoRequestIsValid();

            $this->assertEmpty($this->kassa->createPaymentAvisoResponse($this->requestData)->getContent());
        });
    }


    private function makeCheckOrderResponseXml(array $data)
    {
        return $this->makeResponseXml('checkOrderResponse', $data);
    }

    private function makePaymentAvisoResponseXml(array $data)
    {
        return $this->makeResponseXml('paymentAvisoResponse', $data);
    }

    private function makeResponseXml($tagName, $data)
    {
        $xml = simplexml_load_string("<?xml version=\"1.0\" encoding=\"UTF-8\"?><$tagName />");
        $xml['shopId'] = $this->shopId;
        $xml['invoiceId'] = $this->invoiceId;
        $xml['performedDatetime'] = $this->performedDatetime;

        foreach ($data as $attributeName => $attributeValue) {
            $xml[$attributeName] = $attributeValue;
        }

        return $xml->asXML();
    }

    private function expectsCheckOrderCallbackReturn($returnValue)
    {
        $this->requestHandler->shouldReceive('checkOrder')->andReturn($returnValue);
    }

    private function expectsPaymentAvisoCallbackReturn($returnValue)
    {
        $this->requestHandler->shouldReceive('paymentAviso')->andReturn($returnValue);
    }

    private function expectsCheckRequestIsValid()
    {
        $this->checkOrderRequest->shouldReceive('isValid')->andReturn(true);
    }

    private function expectsCheckRequestIsInvalid()
    {
        $this->checkOrderRequest->shouldReceive('isValid')->andReturn(false);
    }

    private function expectsPaymentAvisoRequestIsValid()
    {
        $this->paymentAvisoRequest->shouldReceive('isValid')->andReturn(true);
    }

    private function expectsPaymentAvisoRequestIsInvalid()
    {
        $this->paymentAvisoRequest->shouldReceive('isValid')->andReturn(false);
    }

    private function fillCheckOrderRequest($requestData)
    {
        foreach ($requestData as $attribute => $value) {
            $this->checkOrderRequest->$attribute = $value;
        }
    }

    private function fillPaymentAvisoRequest($requestData)
    {
        foreach ($requestData as $attribute => $value) {
            $this->paymentAvisoRequest->$attribute = $value;
        }
    }
}