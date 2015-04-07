<?php

namespace sergasd\yandexkassa;

class PaymentAvisoRequest extends YandexKassaRequestBase
{
    /** @inheritdoc */
    public $action = 'paymentAviso';

    /** @var string Момент регистрации оплаты заказа в ИС Оператора */
    public $paymentDatetime;

    /** @var string Двухбуквенный код страны плательщика в соответствии с ISO 3166-1 */
    public $cps_user_country_code;
}