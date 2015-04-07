<?php

namespace sergasd\yandexkassa;

class CheckOrderRequest extends YandexKassaRequestBase
{
    /** @inheritdoc */
    public $action = 'checkOrder';
}
