<?php

namespace sergasd\yandexkassa;

class PaymentType
{
    const TYPE_PC = 'PC';

    const TYPE_AC = 'AC';

    const TYPE_MC = 'MC';

    const TYPE_GP = 'GP';

    const TYPE_WM = 'WM';

    const TYPE_SB = 'SB';

    const TYPE_MP = 'MP';

    const TYPE_AB = 'AB';

    const TYPE_МА = 'МА';

    const TYPE_PB = 'PB';
    
    public static function getTypeLabels()
    {
        return [
            self::TYPE_PC => 'Оплата из кошелька в Яндекс.Деньгах',
            self::TYPE_AC => 'Оплата с произвольной банковской карты',
            self::TYPE_MC => 'Платеж со счета мобильного телефона',
            self::TYPE_GP => 'Оплата наличными через кассы и терминалы',
            self::TYPE_WM => 'Оплата из кошелька в системе WebMoney',
            self::TYPE_SB => 'Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн',
            self::TYPE_MP => 'Оплата через мобильный терминал (mPOS)',
            self::TYPE_AB => 'Оплата через Альфа-Клик',
            self::TYPE_МА => 'Оплата через MasterPass',
            self::TYPE_PB => 'Оплата через Промсвязьбанк',
        ];
    }

    public static function getAllowedPayMethodsList($allowedPayMethods = [])
    {
        return array_intersect_key(static::getTypeLabels(), array_flip($allowedPayMethods));
    }
}