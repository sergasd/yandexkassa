<?php

namespace sergasd\yandexkassa;

/**
 * Обработчик запросов от Yandex кассы должен реализовать этот интерфейс
 */
interface IRequestHandler
{
    /**
     * Метод вызывается при проверке заказа
     * Если метод вернет false - это будет означать невозможность оплаты заказа
     * Если метод вернет true - это будет означать что заказ может быть оплачен
     *
     * @param $request
     * @return boolean
     */
    public function checkOrder(CheckOrderRequest $request);

    /**
     * Метод вызывается при уведомлении о платеже
     * Если метод вернет false - это будет означать ошибку при разборе запроса. Такой запрос больше не будет повторяться
     * Если метод вернет true - это будет означать что все ОК. Запрос для одного и того же invoiceId может повторяться
     * в этом случае следует вернуть true
     *
     * @param $request
     * @return boolean
     */
    public function paymentAviso(PaymentAvisoRequest $request);
}