<?php
/**
 * @var string $formUrl
 * @var string $shopId
 * @var string $scid
 * @var string $sum
 * @var string $customerNumber
 * @var string $customerEmail
 * @var array $allowedPayMethodsList
 * @var string $orderNumber
 */
?>

<form action="<?= $formUrl ?>" method="post">

    <!-- Обязательные поля -->
    <input name="ShopId" value="<?= CHtml::encode($shopId)?>" type="hidden"/>
    <input name="scid" value="<?= CHtml::encode($scid) ?>" type="hidden"/>
    <input name="Sum" value="<?= CHtml::encode($sum) ?>" type="hidden">
    <input name="CustomerNumber" value="<?= CHtml::encode($customerNumber) ?>" type="hidden"/>

    <!-- Необязательные поля -->
    <?= CHtml::dropDownList('paymentType', '', $allowedPayMethodsList, ['empty' => 'Выберите способ оплаты']) ?>
    <input name="orderNumber" value="<?= $orderNumber ?>" type="hidden"/>
    <input name="cps_email" value="<?= CHtml::encode($customerEmail) ?>" type="hidden"/>

    <input type="submit" value="Оплатить" />
</form>