<?php

BsExtensionManager::registerExtension('InsertLink', BsRUNLEVEL::FULL|BsRUNLEVEL::REMOTE);

$wgAutoloadClasses['InsertLink'] = __DIR__ . '/InsertLink.class.php';

$wgMessagesDirs['InsertLink'] = __DIR__ . '/i18n';

$aResourceModuleTemplate = array(
	'localBasePath' => __DIR__.'/resources',
	'remoteExtPath' => 'BlueSpiceExtensions/InsertLink/resources'
);

$wgResourceModules['ext.bluespice.insertlink'] = array(
	'scripts' => 'bluespice.insertLink.js',
	'messages' => array(
		'bs-insertlink-button-title',
		'bs-insertlink-dialog-title',
		'bs-insertlink-tab-wiki-page',
		'bs-insertlink-tab-ext-link',
		'bs-insertlink-tab-email',
		'bs-insertlink-tab-ext-file',
		'bs-insertlink-label-page',
		'bs-insertlink-label-link',
		'bs-insertlink-label-mail',
		'bs-insertlink-label-description',
		'bs-insertlink-label-file',
		'bs-insertlink-label-searchfile',
		'bs-insertlink-select-a-page',
		'bs-insertlink-select-a-namespace',
		'bs-insertlink-empty-field-text',
		'bs-insertlink-applet-title',
		'bs-insertlink-no-applet'
	),
	'dependencies' => array(
		'ext.bluespice.extjs'
	)
) + $aResourceModuleTemplate;

$wgResourceModules['ext.bluespice.insertlink.styles'] = array(
	'styles' => 'bluespice.insertLink.css',
	'position' => 'top'
) + $aResourceModuleTemplate;

unset( $aResourceModuleTemplate );

$wgHooks['LoadExtensionSchemaUpdates'][] = 'InsertLink::onLoadExtensionSchemaUpdates';