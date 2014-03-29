<?php
if (!class_exists('msPaymentInterface')) {
    require_once dirname(dirname(dirname(__FILE__))) . '/model/minishop2/mspaymenthandler.class.php';
}

class Platron extends msPaymentHandler implements msPaymentInterface {

    function __construct(xPDOObject $object, $config = array()) {
        $this->modx = & $object->xpdo;

        //assets_url already include base_url
        $hostUrl = $this->modx->getOption('url_scheme') . $this->modx->getOption('http_host');
        $assetsUrl = $this->modx->getOption('minishop2.assets_url', $config, $this->modx->getOption('assets_url').'components/minishop2/');
        $resultScript = 'platron.php';
        $resultUrl = $hostUrl . $assetsUrl . 'payment/' . $resultScript;

        $this->config = array_merge(array(
            'result_url' => $resultUrl
            ,'result_script' => $resultScript
            ,'checkout_url' => $this->modx->getOption('ms2_payment_platron_checkout_url', null, 'https://www.platron.ru/payment.php')
            ,'currency' => $this->modx->getOption('ms2_payment_platron_currency', null, 'RUR')
            ,'merchant_id' => $this->modx->getOption('ms2_payment_platron_merchant_id')
            ,'secret_key' => $this->modx->getOption('ms2_payment_platron_secret_key')
            ,'payment_system' => $this->modx->getOption('ms2_payment_platron_payment_system')
            ,'lifetime' => $this->modx->getOption('ms2_payment_platron_lifetime',null, 86400)
            ,'language' => $this->modx->getOption('ms2_payment_platron_language', null, 'ru')
            ,'submit_user_fields' => $this->modx->getOption('ms2_payment_platron_submit_user_fields', null, 'user_email,user_contact_email,user_phone')
            ,'json_response' => false
        ), $config);

        $this->config['submit_fields'] = array_map('trim', explode(',', $this->config['submit_fields']));

        $this->modx->lexicon->load('minishop2:platron');
    }


    /* @inheritdoc} */
    public function send(msOrder $order) {
        $link = $this->getPaymentLink($order);
        return $this->success('', array('redirect' => $link));
    }

    /**
     * Generate payment link
     *
     * @param msOrder $order
     * @return string
     */
    public function getPaymentLink(msOrder $order) {
        $successUrl = $failureUrl = $this->modx->getOption('site_url');
        $params = array(
            'msorder' => $order->get('id')
        );
        $context = $order->get('context');
        if ($id = $this->modx->getOption('ms2_payment_platron_success_id', null, 0)) {
            $successUrl = $this->modx->makeUrl($id, $context, $params, 'full', array('xhtml_urls' => false));
        }
        if ($id = $this->modx->getOption('ms2_payment_platron_cancel_id', null, 0)) {
            $failureUrl = $this->modx->makeUrl($id, $context, $params, 'full', array('xhtml_urls' => false));
        }

        $params = array(
            'pg_merchant_id' => $this->config['merchant_id']
            ,'pg_order_id' => $order->get('id')
            ,'pg_amount' => $order->get('cost')
            ,'pg_currency' => $this->config['currency']
            ,'pg_result_url' => $this->config['result_url']
            ,'pg_request_methos' => 'POST'
            ,'pg_success_url' => $successUrl
            ,'pg_failure_url' => $failureUrl
            ,'pg_payment_system' => $this->config['payment_system']
            ,'pg_lifetime' => $this->config['lifetime']
            ,'pg_description' => $this->modx->lexicon('ms2_payment_platron_order_description', array('num' => $order->get('id')))
            ,'pg_language' => $this->config['language']
            ,'pg_salt' => uniqid(mt_rand(), true)
        );

        $submitFields = array_map('trim', explode(',', $this->config['submit_user_fields']));
        if (sizeof($submitFields)) {
            $profile = $order->getOne('UserProfile');
            if (isset($profile)) {
                $fieldMap = array(
                    'user_email' => 'email'
                    ,'user_contact_email' => 'email'
                    ,'user_phone' => 'phone'
                );
                foreach($fieldMap as $platronField => $modxField) {
                    if (in_array($platronField, $submitFields)) {
                        $v = $profile->get($modxField);
                        if (!empty($v))
                            $params['pg_'.$platronField] = $v;
                    }
                }
            }
        }

        $orderProperties = $order->get('properties');
        if (isset($orderProperties['payments']['platron']['payment_system']))
            $params['pg_payment_system'] = $orderProperties['payments']['platron']['payment_system'];
        elseif (!empty($this->config['payment_system']))
            $params['pg_payment_system'] = $this->config['payment_system'];

        $params['pg_sig'] = $this->signature($params, 'payment.php');

        $link = $this->config['checkout_url'];
        $link.= ((strpos($link, '?') === false)?'?':'&') . http_build_query($params);

        return $link;
    }

    /* @inheritdoc} */
    public function receive(msOrder $order, $params = array()) {
        //Check input parameters
        if (!isset($params['pg_result'], $params['pg_salt'], $params['pg_sig'])) {
            return $this->paymentError('Wrong payment request', $params);
        }
        if ($this->signature($params, $this->config['result_script']) != $params['pg_sig']) {
            return $this->paymentError('Wrong signature', $params);
        }

        /* @var miniShop2 $miniShop2 */
        $miniShop2 = $this->modx->getService('miniShop2');

        @$this->modx->context->key = 'mgr';

        if ($params['pg_result']==1)
            $miniShop2->changeOrderStatus($order->get('id'), 2); // Setting status "paid"
        elseif ($params['pg_result']==0 && $this->modx->getOption('ms2_payment_platron_cancel_order', null, false))
            $miniShop2->changeOrderStatus($order->get('id'), 4); // Setting status "cancelled"

        return $this->buildResponse('ok', $this->config['result_script']);
    }


    /**
     * Caclulate signature
     *
     * @param array $params Params for signature
     * @param string $script Current script filename
     * @return string
     */
    public function signature($params = array(), $script) {

        unset($params['pg_sig']);
        ksort($params);
        array_unshift($params, $script);
        $params[] = $this->config['secret_key'];
        return md5(implode(';', $params));
    }

    /**
     * Process error
     *
     * @param string $text Text to log
     * @param array $params Request parameters
     * @return bool
     */
    public function paymentError($text, $params = array()) {
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, '[miniShop2:Platron] ' . $text . ' Request: ' . print_r($params, true));
        return $this->buildResponse('error', $this->config['result_script'], $text);
    }

    /**
     * Build response for result
     *
     * @param sting $status Status of operation (ok or error)
     * @param $script Script filename for calculate signature
     * @param string $description Description for status (for example error description)
     * @return string
     */
    public function buildResponse($status, $script, $description = '') {
        $params = array(
            'pg_status' => $status
            ,'pg_salt' => uniqid(mt_rand(), true)
        );
        if (!empty($description))
            $params['pg_description'] = $description;
        $params['pg_sig'] = $this->signature($params, $script);

        $response = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<response/>");
        foreach($params as $k=>$v) {
            $response->addChild($k, $v);
        }
        return (string)$response->asXML();
    }
}