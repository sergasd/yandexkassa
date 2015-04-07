<?php

namespace sergasd\yandexkassa;

class RequestFactory
{
    /** @var YandexKassa */
    private $kassa;

    public function __construct(YandexKassa $kassa)
    {
        $this->kassa = $kassa;
    }

    public function createCheckOrderRequest(array $requestData)
    {
        $request = new CheckOrderRequest($this->kassa->shopPassword);
        $this->configure($request, $requestData);
        return $request;
    }

    public function createPaymentAvisoRequest(array $requestData)
    {
        $request = new PaymentAvisoRequest($this->kassa->shopPassword);
        $this->configure($request, $requestData);
        return $request;
    }

    private function configure($object, array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists(get_class($object), $key)) {
                $object->$key = (string) $value;
            } else {
                $object->customFields[$key] = (string) $value;
            }
        }
    }
}