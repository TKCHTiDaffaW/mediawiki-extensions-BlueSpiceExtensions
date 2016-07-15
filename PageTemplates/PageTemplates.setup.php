<?php

BsExtensionManager::registerExtension( 'PageTemplates', BsRUNLEVEL::FULL|BsRUNLEVEL::REMOTE );

$wgMessagesDirs['PageTemplates'] = __DIR__ . '/i18n';

$wgAutoloadClasses['PageTemplates'] = __DIR__ . '/PageTemplates.class.php';
$wgAutoloadClasses['BSApiPageTemplatesStore'] = __DIR__ . '/includes/api/BSApiPageTemplatesStore.php';
$wgAutoloadClasses['BSApiPageTemplatesTasks'] = __DIR__ . '/includes/api/BSApiPageTemplatesTasks.php';

$wgResourceModules['ext.bluespice.pageTemplates'] = array(
	'scripts' => 'extensions/BlueSpiceExtensions/PageTemplates/resources/bluespice.pageTemplates.js',
	'dependencies' => 'ext.bluespice.extjs',
	'messages' => array(
		'bs-pagetemplates-headerlabel',
		'bs-pagetemplates-headertargetnamespace',
		'bs-pagetemplates-tipeditdetails',
		'bs-pagetemplates-tipdeletetemplate',
		'bs-pagetemplates-tipaddtemplate',
		'bs-pagetemplates-label-tpl',
		'bs-pagetemplates-label-desc',
		'bs-pagetemplates-label-targetns',
		'bs-pagetemplates-label-article',
		'bs-pagetemplates-confirm-deletetpl',
		'bs-pagetemplates-remove-message-unknown',
		'bs-pagetemplates-remove-message-success',
		'bs-pagetemplates-remove-message-failure'
	),
	'localBasePath' => $IP,
	'remoteBasePath' => &$GLOBALS['wgScriptPath']
);

$wgAutoloadClasses['PageTemplatesAdmin'] = __DIR__ . '/PageTemplatesAdmin.class.php';
$wgAPIModules['bs-pagetemplates-store'] = 'BSApiPageTemplatesStore';
$wgAPIModules['bs-pagetemplates-tasks'] = 'BSApiPageTemplatesTasks';

$wgAjaxExportList[] = 'PageTemplatesAdmin::doEditTemplate';
$wgAjaxExportList[] = 'PageTemplatesAdmin::doDeleteTemplate';
$wgAjaxExportList[] = 'PageTemplatesAdmin::doDeleteTemplates';

$wgHooks['LoadExtensionSchemaUpdates'][] = 'PageTemplates::getSchemaUpdates';