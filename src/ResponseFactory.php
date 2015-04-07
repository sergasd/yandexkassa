<?php

namespace sergasd\yandexkassa;

class ResponseFactory
{
    private $kassa;

    public function __construct(YandexKassa $kassa)
    {
        $this->kassa = $kassa;
    }

    public function createCheckOrderResponse(CheckOrderRequest $request, $performedDatetime = null)
    {
        $response = new YandexKassaResponse('checkOrderResponse');
        $response->shopId = $request->shopId;
        $response->invoiceId = $request->invoiceId;
        $response->performedDatetime = $performedDatetime ?: $request->requestDatetime;

        return $response;
    }

    public function createPaymentAvisoResponse(PaymentAvisoRequest $request, $performedDatetime = null)
    {
        $response = new YandexKassaResponse('paymentAvisoResponse');
        $response->shopId = $request->shopId;
        $response->invoiceId = $request->invoiceId;
        $response->performedDatetime = $performedDatetime ?: $request->requestDatetime;

        return $response;
    }
}