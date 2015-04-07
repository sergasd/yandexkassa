<?php

namespace sergasd\yandexkassa;

use Codeception\Specify;
use PHPUnit_Framework_TestCase;

class YandexKassaResponseTest extends PHPUnit_Framework_TestCase
{
    use Specify;

    private $tagName = 'checkOrderResponse';

    private $performedDatetime = '2011-05-04T20:38:00.000+04:00';

    private $shopId = 'my shop id';

    private $invoiceId = ' my invoice id';

    public function testGetContent()
    {
        $this->specify('It should return success response', function () {
            $response = $this->createResponse($this->tagName);
            $response->markAsSuccess();
            $expected = $this->makeCheckOrderResponseXml($this->tagName, [
                'code' => YandexKassaResponse::STATUS_SUCCESS,
            ]);
            $this->assertXmlStringEqualsXmlString($expected, $response->getContent());
        });

        $this->specify('It should return invalid response when it mark as invalid', function () {
            $response = $this->createResponse($this->tagName);
            $response->markAsInvalid();
            $expected = $this->makeCheckOrderResponseXml($this->tagName, [
                'code' => YandexKassaResponse::STATUS_AUTHORIZE_ERROR,
                'message' => YandexKassaResponse::MESSAGE_INVALID_REQUEST,
            ]);
            $this->assertXmlStringEqualsXmlString($expected, $response->getContent());
        });

        $this->specify('It should return invalid response when it mark refuse accept transfer', function () {
            $response = $this->createResponse($this->tagName);
            $response->markAsRefuseAcceptTransfer();
            $expected = $this->makeCheckOrderResponseXml($this->tagName, [
                'code' => YandexKassaResponse::STATUS_REFUSE_ACCEPT_TRANSFER,
                'message' => YandexKassaResponse::MESSAGE_REFUSE_ACCEPT_TRANSFER,
            ]);
            $this->assertXmlStringEqualsXmlString($expected, $response->getContent());
        });

        $this->specify('It should return empty response when it mark as empty', function () {
            $response = $this->createResponse($this->tagName);
            $response->markAsEmpty();
            $this->assertEmpty($response->getContent());
        });

        $this->specify('Response should created with another tag name', function () {
            $tagName = 'my_tag_name';
            $response = $this->createResponse($tagName);
            $response->markAsSuccess();
            $expected = $this->makeCheckOrderResponseXml($tagName, [
                'code' => YandexKassaResponse::STATUS_SUCCESS,
            ]);
            $this->assertXmlStringEqualsXmlString($expected, $response->getContent());
        });

    }

    private function createResponse($tagName)
    {
        $response = new YandexKassaResponse($tagName);
        $response->performedDatetime = $this->performedDatetime;
        $response->shopId = $this->shopId;
        $response->invoiceId = $this->invoiceId;
        return $response;
    }

    private function makeCheckOrderResponseXml($tagName, array $data)
    {
        $xml = simplexml_load_string("<?xml version=\"1.0\" encoding=\"UTF-8\"?><$tagName />");
        $xml['performedDatetime'] = $this->performedDatetime;
        $xml['shopId'] = $this->shopId;
        $xml['invoiceId'] = $this->invoiceId;

        foreach ($data as $attributeName => $attributeValue) {
            $xml[$attributeName] = $attributeValue;
        }
        return $xml->asXML();
    }
}