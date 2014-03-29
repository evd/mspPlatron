<?php
define('MODX_API_MODE', true);
require dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php';

$modx->getService('error','error.modError');

//Logging request if in debug mode
if ($modx->getDebug()) $modx->log(xPDO::LOG_LEVEL_DEBUG, '[miniShop2:Platron] Payment notification request: ' . print_r($_REQUEST, true));

/* @var miniShop2 $miniShop2 */
$miniShop2 = $modx->getService('minishop2','miniShop2',$modx->getOption('minishop2.core_path',null,$modx->getOption('core_path').'components/minishop2/').'model/minishop2/', array());
$miniShop2->loadCustomClasses('payment');

$response = '';
if (class_exists('Platron')) {
    /* @var msPaymentInterface|Platron $handler */
    $handler = new Platron($modx->newObject('msOrder'));
    if (!empty($_REQUEST['pg_order_id'])) {
        $order = $modx->getObject('msOrder', $_REQUEST['pg_order_id']);
        if (isset($order)) {
            $response = $handler->receive($order, $_REQUEST);
        } else
            $response = $handler->paymentError('Order not found', $_REQUEST);
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, '[miniShop2:Platron] Wrong orderId.');
    }
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR, '[miniShop2:Platron] could not load payment class "Platron".');
}

echo $response;