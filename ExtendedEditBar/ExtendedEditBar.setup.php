<?php

BsExtensionManager::registerExtension('ExtendedEditBar',                 BsRUNLEVEL::FULL|BsRUNLEVEL::REMOTE);

$wgExtensionMessagesFiles['ExtendedEditBar'] = __DIR__ . '/languages/ExtendedEditBar.i18n.php';

$aResourceModuleTemplate = array(
	'localBasePath' => $IP.'/extensions/BlueSpiceExtensions/ExtendedEditBar/resources',
	'remoteExtPath' => 'BlueSpiceExtensions/ExtendedEditBar/resources',
);

$wgResourceModules['ext.bluespice.extendeditbar'] = array(
	'scripts' => 'bluespice.extendedEditBar.js',
	'dependencies' => 'mediawiki.action.edit',
) + $aResourceModuleTemplate;

$wgResourceModules['ext.bluespice.extendeditbar.styles'] = array(
	'styles' => 'bluespice.extendedEditBar.css'
) + $aResourceModuleTemplate;

unset( $aResourceModuleTemplate );
