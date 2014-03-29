<?php
/**
 * Loads system settings into build
 *
 * @package mspplatron
 * @subpackage build
 */
$settings = array();

$tmp = array(
    'ms2_payment_platron_checkout_url' => array(
        'value' => 'https://www.platron.ru/payment.php'
        ,'xtype' => 'textfield'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_currency' => array(
        'value' => 'RUR'
        ,'xtype' => 'textfield'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_merchant_id' => array(
        'value' => ''
        ,'xtype' => 'textfield'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_secret_key' => array(
        'value' => ''
        ,'xtype' => 'text-password'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_payment_system' => array(
        'value' => ''
        ,'xtype' => 'textfield'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_language' => array(
        'value' => 'ru'
        ,'xtype' => 'textfield'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_lifetime' => array(
        'value' => '86400'
        ,'xtype' => 'numberfield'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_submit_user_fields' => array(
        'value' => 'user_email,user_contact_email,user_phone'
        ,'xtype' => 'textfield'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_success_id' => array(
        'value' => ''
        ,'xtype' => 'numberfield'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_cancel_id' => array(
        'value' => ''
        ,'xtype' => 'numberfield'
        ,'area' => 'ms2_payment'
    )
    ,'ms2_payment_platron_cancel_order' => array(
        'value' => false
        ,'xtype' => 'combo-boolean'
        ,'area' => 'ms2_payment'
    )
);


foreach ($tmp as $k => $v) {
    /* @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => $k
            ,'namespace' => 'minishop2'
        ), $v
    ),'',true,true);

    $settings[] = $setting;
}

return $settings;