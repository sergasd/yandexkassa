<?php

namespace sergasd\yandexkassa\bridge\yii1;

use CApplicationComponent;
use CException;
use sergasd\yandexkassa\IRequestHandler;
use sergasd\yandexkassa\PaymentType;
use sergasd\yandexkassa\YandexKassa;
use Yii;

class Yii1YandexKassa extends CApplicationComponent
{
    /** @var string Идентификатор Контрагента */
    public $shopId = 'shop id';

    /** @var string Номер витрины Контрагента */
    public $scid = 'sc id';

    /** @var string секретный ключ */
    public $shopPassword = 'shopPassword';

    /** @var IRequestHandler */
    public $requestHandler;

    /**
     * @var string урл для отправки формы
     * Тестовый режим: https://demomoney.yandex.ru/eshop.xml
     * Продакшн режим: https://money.yandex.ru/eshop.xml
     */
    public $formUrl = 'https://money.yandex.ru/eshop.xml';

    /**
     * @var array Доступные методы оплаты
     * @see https://money.yandex.ru/doc.xml?id=526537
     */
    public $allowedPayMethods = ['PC', 'AC', 'MC', 'GP', 'WM', 'SB', 'MP', 'AB', 'МА', 'PB'];

    /** @var YandexKassa */
    private $kassa;

    public function init()
    {
        parent::init();
        $requestHandler = Yii::createComponent($this->requestHandler);

        if (!$requestHandler instanceof IRequestHandler) {
            throw new CException("requestHandler must implement IRequestHandler interface");
        }

        $this->kassa = new YandexKassa($requestHandler);
        $this->kassa->shopId = $this->shopId;
        $this->kassa->scid = $this->scid;
        $this->kassa->shopPassword = $this->shopPassword;
    }

    public function createCheckOrderResponse(array $requestData)
    {
        return $this->kassa->createCheckOrderResponse($requestData);
    }

    public function createPaymentAvisoResponse(array $requestData)
    {
        return $this->kassa->createPaymentAvisoResponse($requestData);
    }

    public function getAllowedPayMethodsList()
    {
        return PaymentType::getAllowedPayMethodsList($this->allowedPayMethods);
    }
}