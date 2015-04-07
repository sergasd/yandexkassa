<?php

namespace sergasd\yandexkassa\tests;

use sergasd\yandexkassa\RequestFactory;
use sergasd\yandexkassa\YandexKassa;
use Codeception\Specify;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

class RequestFactoryTest extends PHPUnit_Framework_TestCase
{
    use Specify;

    /** @var YandexKassa | MockInterface */
    private $kassa;

    /** @var RequestFactory */
    private $requestFactory;

    private $secret = 'my shopPassword';

    protected function setUp()
    {
        parent::setUp();
        $this->kassa = Mockery::mock('sergasd\yandexkassa\YandexKassa');
        $this->kassa->shopPassword = $this->secret;
        $this->requestFactory = new RequestFactory($this->kassa);
    }

    public function testCreateCheckOrderRequest()
    {
        $this->specify('It should create check order request with properties', function () {
            $requestData = [
                'requestDatetime' => '2011-05-04T20:38:00.000+04:00',
                'action' => 'checkOrder',
                'md5' => '8256D2A032A35709EAF156270C9EFE2E',
                'shopId' => '13',
                'shopArticleId' => '456',
                'invoiceId' => '1234567',
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
                'myCustomField1' => 'value1',
                'myCustomField2' => 'value2',
            ];
            $request = $this->requestFactory->createCheckOrderRequest($requestData);

            foreach ($requestData as $attributeName => $value) {
                if (property_exists(get_class($request), $attributeName)) {
                    $this->assertEquals($value, $request->$attributeName);
                }
            }

            $this->assertEquals('value1', $request->customFields['myCustomField1']);
            $this->assertEquals('value2', $request->customFields['myCustomField2']);

            $this->assertEquals($this->secret, $this->readAttribute($request, 'secretKey'));
        });
    }

    public function testCreatePaymentAvisoRequest()
    {
        $this->specify('It should create payment aviso request with properties', function () {
            $requestData = [
                'requestDatetime' => '2011-05-04T20:38:00.000+04:00',
                'action' => 'checkOrder',
                'md5' => '8256D2A032A35709EAF156270C9EFE2E',
                'shopId' => '13',
                'shopArticleId' => '456',
                'invoiceId' => '1234567',
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
                'myCustomField1' => 'value1',
                'myCustomField2' => 'value2',
            ];
            $request = $this->requestFactory->createPaymentAvisoRequest($requestData);

            foreach ($requestData as $attributeName => $value) {
                if (property_exists(get_class($request), $attributeName)) {
                    $this->assertEquals($value, $request->$attributeName);
                }
            }

            $this->assertEquals('value1', $request->customFields['myCustomField1']);
            $this->assertEquals('value2', $request->customFields['myCustomField2']);

            $this->assertEquals($this->secret, $this->readAttribute($request, 'secretKey'));
        });
    }
}
