<?php

namespace sergasd\yandexkassa;

abstract class YandexKassaRequestBase
{
    /** @var string Момент формирования запроса в ИС Оператора */
    public $requestDatetime;

    /** @var string Тип запроса */
    public $action = '';

    /** @var string MD5-хэш параметров платежной формы */
    public $md5;

    /** @var string Идентификатор Контрагента, присваиваемый Оператором */
    public $shopId;

    /** @var string Идентификатор товара, присваиваемый Оператором */
    public $shopArticleId;

    /** @var string Уникальный номер транзакции в ИС Оператора */
    public $invoiceId;

    /** @var string Номер заказа в ИС Контрагента */
    public $orderNumber;

    /** @var string Идентификатор плательщика (присланный в платежной форме) на стороне Контрагента */
    public $customerNumber;

    /** @var string Момент регистрации заказа в ИС Оператора */
    public $orderCreatedDatetime;

    /** @var string Стоимость заказа */
    public $orderSumAmount;

    /** @var string Код валюты для суммы заказа */
    public $orderSumCurrencyPaycash;

    /** @var string Код процессингового центра Оператора для суммы заказа */
    public $orderSumBankPaycash;

    /** @var string Сумма к выплате Контрагенту на р/с (стоимость заказа минус комиссия Оператора) */
    public $shopSumAmount;

    /** @var string Код валюты для shopSumAmount */
    public $shopSumCurrencyPaycash;

    /** @var string Код процессингового центра Оператора для shopSumAmount */
    public $shopSumBankPaycash;

    /** @var string Номер счета в ИС Оператора, с которого производится оплата */
    public $paymentPayerCode;

    /** @var string Способ оплаты заказа */
    public $paymentType;

    /** @var array Параметры, добавленные Контрагентом в платежную форму */
    public $customFields = [];

    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function isValid()
    {
        $md5Data = [
            $this->action,
            $this->orderSumAmount,
            $this->orderSumCurrencyPaycash,
            $this->orderSumBankPaycash,
            $this->shopId,
            $this->invoiceId,
            $this->customerNumber,
            $this->secretKey,
        ];
        $md5 = md5(implode(';', $md5Data));

        return (mb_strtoupper($this->md5) === mb_strtoupper($md5));
    }
}