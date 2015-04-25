<?php

namespace sergasd\yandexkassa\bridge\yii1\widgets\payform;

use Yii;
use CWidget;
use sergasd\yandexkassa\bridge\yii1\Yii1YandexKassa;

class PayFormWidget extends CWidget
{
    public $viewFile = 'index';

    public $yandexKassaId = 'yandexKassa';

    public $customerNumber;

    public $orderNumber;

    public $sum;

    public $customerEmail;

    public $customerPhone;

    public $additionalViewData = [];

    public function run()
    {
        /** @var Yii1YandexKassa $yandexKassa */
        $yandexKassa = Yii::app()->getComponent($this->yandexKassaId);

        $viewData = array_merge([
            'shopId' => $yandexKassa->shopId,
            'scid' => $yandexKassa->scid,
            'customerNumber' => $this->customerNumber ?: Yii::app()->user->id,
            'customerEmail' => $this->customerEmail,
            'orderNumber' => $this->orderNumber,
            'sum' => $this->sum,
            'formUrl' => $yandexKassa->formUrl,
            'allowedPayMethodsList' => $yandexKassa->getAllowedPayMethodsList(),
        ], $this->additionalViewData);

        $this->render($this->viewFile, $viewData);
    }
}