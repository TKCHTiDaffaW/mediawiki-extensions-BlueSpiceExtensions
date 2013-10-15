<?php
BsExtensionManager::registerExtension('FormattingHelp', BsRUNLEVEL::FULL|BsRUNLEVEL::REMOTE);

$wgExtensionMessagesFiles['FormattingHelp'] = __DIR__ . '/languages/FormattingHelp.i18n.php';

$aResourceModuleTemplate = array(
	'localBasePath' => __DIR__ . '/resources',
	'remoteExtPath' => 'BlueSpiceExtensions/FormattingHelp/resources'
);

$wgResourceModules['ext.bluespice.formattinghelp'] = array(
	'scripts' => 'bluespice.formattinghelp.js',
	'messages' => array(
		'bs-formattinghelp-formatting'
	),
	'dependencies' => 'mediawiki.action.edit',
) + $aResourceModuleTemplate;

$wgResourceModules['ext.bluespice.formattinghelp.styles'] = array(
	'styles' => 'bluespice.formattinghelp.css',
) + $aResourceModuleTemplate;

unset($aResourceModuleTemplate);

$wgAjaxExportList[] = 'FormattingHelp::getFormattingHelp';
