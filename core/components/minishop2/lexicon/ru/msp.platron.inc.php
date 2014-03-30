<?php
/**
 * Settings Russian Lexicon Entries for mspPlatron
 *
 * @package mspplatron
 * @subpackage lexicon
 */

$_lang['ms2_payment_platron_order_description'] = 'Заказ #[[+num]]';

$_lang['setting_ms2_payment_platron_checkout_url'] = 'Url оплаты Platron';
$_lang['setting_ms2_payment_platron_checkout_url_desc'] = 'Url для отправки запроса на оплату в системе Platron.';
$_lang['setting_ms2_payment_platron_currency'] = 'Валюта Platron';
$_lang['setting_ms2_payment_platron_currency_desc'] = 'Валюта в которой производится оплата в системе Platron. Возможные значения: USD, RUR, EUR.';
$_lang['setting_ms2_payment_platron_merchant_id'] = 'Идентификатор магазина в Platron';
$_lang['setting_ms2_payment_platron_merchant_desc'] = 'Обязательный уникальный номер магазина в системе Platron. Узнать его можно в личном кабинете Platron.';
$_lang['setting_ms2_payment_platron_secret_key'] = 'Секретный ключ Platron';
$_lang['setting_ms2_payment_platron_secret_key_desc'] = 'Обязательный секретный ключ для подтверждения всех транзакций. Его можно указать в личном кабинете Platron.';
$_lang['setting_ms2_payment_platron_language'] = 'Язык интерфейса Platron';
$_lang['setting_ms2_payment_platron_language_desc'] = 'Язык интерфейса в системе Platron. Возможные варианты ru или en.';
$_lang['setting_ms2_payment_platron_payment_system'] = 'Наименование платежной системы в Platron';
$_lang['setting_ms2_payment_platron_payment_system_desc'] = 'Способ ввода средств в систему Platron, который будет предложен Плательщику, минуя экран выбора. Подробнее в документации к Platron. Или пустое для выбора пользователем.';
$_lang['setting_ms2_payment_platron_lifetime'] = 'Время жизни заказа в секундах';
$_lang['setting_ms2_payment_platron_lifetime_desc'] = 'Время (в секундах) в течение которого платеж должен быть завершен, в противном случае заказ при проведении платежа Platron откажет платежной системе в проведении. Минимально допустимое значение: 300 секунд (5 минут). Максимально допустимое значение: 604800 секунд (7 суток).';
$_lang['setting_ms2_payment_platron_submit_user_fields'] = 'Поля пользователя, которые передаются в Platron при оплате';
$_lang['setting_ms2_payment_platron_submit_user_fields_desc'] = 'Данные поля передаются в Platron из свойств пользователя. Если магазин не требует обязательного указания телефона или Email, то можно оставить необходимые поля или поле пустым. В этом случае Platron предложит ввести их на странице оплаты. Возможные значения user_email,contact_user_email,user_phone.';
$_lang['setting_ms2_payment_platron_success_id'] = 'Страница успешной оплаты Platron';
$_lang['setting_ms2_payment_platron_success_id_desc'] = 'Пользователь будет отправлен на эту страницу после завершения оплаты. Рекомендуется указать id страницы с корзиной, для вывода заказа.';
$_lang['setting_ms2_payment_platron_cancel_id'] = 'Страница отказа от оплаты Platron';
$_lang['setting_ms2_payment_platron_cancel_id_desc'] = 'Пользователь будет отправлен на эту страницу при неудачной оплате. Рекомендуется указать id страницы с корзиной, для вывода заказа.';
$_lang['setting_ms2_payment_platron_cancel_order'] = 'Отмена заказа Platron';
$_lang['setting_ms2_payment_platron_cancel_order_desc'] = 'Если включено, то заказ будет перевед в статус "Отменён" при отказе от оплаты.';