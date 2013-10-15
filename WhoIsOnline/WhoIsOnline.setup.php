<?php

BsExtensionManager::registerExtension('WhoIsOnline',                     BsRUNLEVEL::FULL|BsRUNLEVEL::REMOTE);

$wgExtensionMessagesFiles['WhoIsOnline'] = __DIR__ . '/WhoIsOnline.i18n.php';

$wgResourceModules['ext.bluespice.whoisonline'] = array(
	'scripts' => 'extensions/BlueSpiceExtensions/WhoIsOnline/resources/WhoIsOnline.js',
	'localBasePath' => $IP,
	'remoteBasePath' => &$GLOBALS['wgScriptPath']
);

$wgAutoloadClasses['ViewWhoIsOnlineTag'] = __DIR__ . '/views/view.WhoIsOnlineTag.php';
$wgAutoloadClasses['ViewWhoIsOnlineItemWidget'] = __DIR__ . '/views/view.WhoIsOnlineItemWidget.php';
$wgAutoloadClasses['ViewWhoIsOnlineWidget'] = __DIR__ . '/views/view.WhoIsOnlineWidget.php';
