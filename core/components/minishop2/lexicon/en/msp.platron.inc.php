<?php
/**
 * Settings English Lexicon Entries for mspPlatron
 *
 * @package mspplatron
 * @subpackage lexicon
 */

$_lang['ms2_payment_platron_order_description'] = 'Order #[[+num]]';

$_lang['setting_ms2_payment_platron_checkout_url'] = 'Platron checkout url';
$_lang['setting_ms2_payment_platron_checkout_url_desc'] = 'Url to request payment in Platron.';
$_lang['setting_ms2_payment_platron_currency'] = 'Platron currency';
$_lang['setting_ms2_payment_platron_currency_desc'] = 'Payment\'s currecnt in Platron. Available values: USD, RUR, EUR.';
$_lang['setting_ms2_payment_platron_merchant_id'] = 'Platron merchant id';
$_lang['setting_ms2_payment_platron_merchant_desc'] = 'Unique identifier of the shop. You can see it in the personal cabinet in Platron.';
$_lang['setting_ms2_payment_platron_secret_key'] = 'Platron secret key';
$_lang['setting_ms2_payment_platron_secret_key_desc'] = 'Mandatory secret key to confirm all transactions. You can set it in the personal cabinet in Platron.';
$_lang['setting_ms2_payment_platron_language'] = 'Platron language';
$_lang['setting_ms2_payment_platron_language_desc'] = 'User interface language. Available values: ru, en.';
$_lang['setting_ms2_payment_platron_payment_system'] = 'Platron preffered payment system';
$_lang['setting_ms2_payment_platron_payment_system_desc'] = 'Preffered input method for payment bypassing selection page. More in Platron documenation. Or empty for user select.';
$_lang['setting_ms2_payment_platron_lifetime'] = 'Order lifetime in seconds';
$_lang['setting_ms2_payment_platron_lifetime_desc'] = 'Time (in seconds) during which the payment must be completed, otherwise the order fails. The minimum allowable value is 300 seconds (5 minutes). Allowed maximum of 604800 seconds (7 days).';
$_lang['setting_ms2_payment_platron_submit_user_fields'] = 'User fields that are submitted to Platron when paying';
$_lang['setting_ms2_payment_platron_submit_user_fields_desc'] = 'These fields are submitted to Platron from user fields. If the store does not require specifying phone or Email, you can leave required fields or blank. In this case Platron prompted to enter their on payment page. Possible values ​​user_email, contact_user_email, user_phone.';
$_lang['setting_ms2_payment_platron_success_id'] = 'Platron successful page id';
$_lang['setting_ms2_payment_platron_success_id_desc'] = 'The customer will be sent to this page after the completion of the payment. It is recommended to specify the id of the page with the shopping cart to order output.';
$_lang['setting_ms2_payment_platron_cancel_id'] = 'Platron cancel page id';
$_lang['setting_ms2_payment_platron_cancel_id_desc'] = 'The customer will be sent to this page if something went wrong. It is recommended to specify the id of the page with the shopping cart to order output.';
$_lang['setting_ms2_payment_platron_cancel_order'] = 'Platron cancel order';
$_lang['setting_ms2_payment_platron_cancel_order_desc'] = 'If true, order will be cancelled if customer cancel payment.';