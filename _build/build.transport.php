<?php
/**
 * Platron Plugin for MiniShop2 build script
 *
 * @package mspplatron
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package */
define('PKG_NAME','mspPlatron');
define('PKG_NAME_LOWER',strtolower(PKG_NAME));
define('PKG_VERSION','1.0.0');
define('PKG_RELEASE','pl');
define('PKG_NAME_LOWER_MINISHOP','minishop2');


/* define sources */
$root = dirname(dirname(__FILE__)).'/';
$sources = array(
	'root' => $root,
    'docs' => $root . 'docs/',
	'build' => $root . '_build/',
	'data' => $root . '_build/data/',
	'resolvers' => $root . '_build/resolvers/',
	'source_assets' => array(
		'components/'.PKG_NAME_LOWER_MINISHOP.'/payment/platron.php'
	),
	'source_core' => array(
		'components/'.PKG_NAME_LOWER_MINISHOP.'/custom/payment/platron.class.php',
		'components/'.PKG_NAME_LOWER_MINISHOP.'/lexicon/en/msp.platron.inc.php',
		'components/'.PKG_NAME_LOWER_MINISHOP.'/lexicon/ru/msp.platron.inc.php'
	)
);

/* override with your own defines here (see build.config.sample.php) */
require_once $sources['build'] . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once $sources['build'] . '/includes/functions.php';

$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
//$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/');
$modx->log(modX::LOG_LEVEL_INFO,'Created Transport Package.');

/* load system settings */
$settings = include $sources['data'].'transport.settings.php';
if (!is_array($settings)) {
	$modx->log(modX::LOG_LEVEL_ERROR,'Could not package in settings.');
	} else {
		$attributes= array(
			xPDOTransport::UNIQUE_KEY => 'key',
			xPDOTransport::PRESERVE_KEYS => true,
			xPDOTransport::UPDATE_OBJECT => false,
		);
		foreach ($settings as $setting) {
		$vehicle = $builder->createVehicle($setting,$attributes);
		$builder->putVehicle($vehicle);
	}
	$modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($settings).' System Settings.');
}
unset($settings,$setting,$attributes);

/* create payment */
$payment= $modx->newObject('msPayment');
$payment->set('id',1);
$payment->set('name','Platron');
$payment->set('active', 0);
$payment->set('class', 'Platron');
$payment->set('rank', 100);

/* create payment vehicle */
$attributes = array(
	xPDOTransport::UNIQUE_KEY => 'name',
	xPDOTransport::PRESERVE_KEYS => false,
	xPDOTransport::UPDATE_OBJECT => false
);
$vehicle = $builder->createVehicle($payment,$attributes);

$modx->log(modX::LOG_LEVEL_INFO,'Adding file resolvers to payment...');
foreach($sources['source_assets'] as $file) {
	$dir = dirname($file) . '/';
	$vehicle->resolve('file',array(
		'source' => $root . 'assets/' . $file,
		'target' => "return MODX_ASSETS_PATH . '{$dir}';",
	));
}
foreach($sources['source_core'] as $file) {
	$dir = dirname($file) . '/';
	$vehicle->resolve('file',array(
		'source' => $root . 'core/'. $file,
		'target' => "return MODX_CORE_PATH . '{$dir}';"
	));
}

$modx->log(modX::LOG_LEVEL_INFO,'Adding in PHP resolvers...');
$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'resolve.uninstall.php',
));

$builder->putVehicle($vehicle);
unset($file, $attributes);

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt')
    ,'license' => file_get_contents($sources['docs'] . 'license.txt')
    ,'readme' => file_get_contents($sources['docs'] . 'readme.txt')
    //'setup-options' => array(
    //'source' => $sources['build'].'setup.options.php',
    //),
));
$modx->log(modX::LOG_LEVEL_INFO,'Added package attributes and setup options.');

/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...');
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();