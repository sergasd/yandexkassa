<?php

namespace sergasd\yandexkassa;

class YandexKassa
{
    /** @var string Идентификатор Контрагента */
    public $shopId = 'shop id';

    /** @var string Номер витрины Контрагента */
    public $scid = 'sc id';

    /** @var string секретный пароль магазина */
    public $shopPassword = 'shopPassword';

    /** @var IRequestHandler */
    private $requestHandler;

    public function __construct(IRequestHandler $requestHandler, RequestFactory $requestFactory = null, ResponseFactory $responseFactory = null)
    {
        $this->requestHandler = $requestHandler;
        $this->requestFactory = $requestFactory ?: new RequestFactory($this);
        $this->responseFactory = $responseFactory ?: new ResponseFactory($this);
    }

    /**
     * @param array $requestData
     * @return YandexKassaResponse
     */
    public function createCheckOrderResponse(array $requestData)
    {
        $request = $this->requestFactory->createCheckOrderRequest($requestData);
        $response = $this->responseFactory->createCheckOrderResponse($request);

        if (!$request->isValid()) {
            return $response->markAsInvalid();
        }

        if (!$this->requestHandler->checkOrder($request)) {
            return $response->markAsRefuseAcceptTransfer();
        }

        return $response->markAsSuccess();
    }

    /**
     * @param array $requestData
     * @return YandexKassaResponse
     */
    public function createPaymentAvisoResponse(array $requestData)
    {
        $request = $this->requestFactory->createPaymentAvisoRequest($requestData);
        $response = $this->responseFactory->createPaymentAvisoResponse($request);

        if (!$request->isValid()) {
            return $response->markAsInvalid();
        }

        if (!$this->requestHandler->paymentAviso($request)) {
            return $response->markAsEmpty();
        }

        return $response->markAsSuccess();
    }
}