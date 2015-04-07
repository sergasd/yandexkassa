<?php

namespace sergasd\yandexkassa;

class YandexKassaResponse
{
    /** Успешно */
    const STATUS_SUCCESS = 0;

    /** Ошибка авторизации */
    const STATUS_AUTHORIZE_ERROR = 1;

    /** Отказ в приеме перевода */
    const STATUS_REFUSE_ACCEPT_TRANSFER = 100;

    /** Ошибка разбора запроса */
    const STATUS_REQUEST_PARSE_ERROR = 200;

    const MESSAGE_INVALID_REQUEST = 'Invalid request';

    const MESSAGE_REFUSE_ACCEPT_TRANSFER = 'Refuse accept transfer';

    const MESSAGE_REQUEST_PARSE_ERROR = 'Request parse error';

    /** @var string Момент обработки запроса */
    public $performedDatetime;

    /** @var string Код результата обработки */
    public $code;

    /** @var string Идентификатор Контрагента */
    public $shopId;

    /** @var string Идентификатор транзакции в ИС Оператора. Должен дублировать поле invoiceId запроса */
    public $invoiceId;

    /** @var string Стоимость заказа в валюте, определенной параметром запроса orderSumCurrencyPaycash */
    public $orderSumAmount;

    /** @var string Текстовое пояснение в случае отказа принять платеж */
    public $message;

    /** @var string Дополнительное текстовое пояснение ответа Контрагента */
    public $techMessage;

    private $tagName = 'checkOrderResponse';

    public function __construct($tagName)
    {
        $this->tagName = $tagName;
    }

    public function getContent()
    {
        if (is_null($this->code)) {
            return '';
        }

        $xml = simplexml_load_string("<?xml version=\"1.0\" encoding=\"UTF-8\"?><$this->tagName />");
        $attributes = ['performedDatetime', 'code', 'shopId', 'invoiceId', 'orderSumAmount', 'message', 'techMessage'];

        foreach ($attributes as $attributeName) {
            if (!is_null($this->$attributeName)) {
                $xml[$attributeName] = $this->$attributeName;
            }
        }

        return $xml->asXML();
    }

    public function markAsSuccess()
    {
        $this->code = self::STATUS_SUCCESS;
        return $this;
    }

    public function markAsInvalid()
    {
        $this->code = self::STATUS_AUTHORIZE_ERROR;
        $this->message = self::MESSAGE_INVALID_REQUEST;
        return $this;
    }

    public function markAsRefuseAcceptTransfer()
    {
        $this->code = self::STATUS_REFUSE_ACCEPT_TRANSFER;
        $this->message = self::MESSAGE_REFUSE_ACCEPT_TRANSFER;
        return $this;
    }

    public function markAsEmpty()
    {
        $this->code = null;
        return $this;
    }
}