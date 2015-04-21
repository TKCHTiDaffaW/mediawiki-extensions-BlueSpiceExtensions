<?php

BsExtensionManager::registerExtension('Notifications', BsRUNLEVEL::FULL|BsRUNLEVEL::REMOTE);

//Do not enable Batch mails - Wont work for BS so far
$wgEchoEnableEmailBatch = false;

// MessageFiles
$wgMessagesDirs['Notifications'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['Notifications'] = __DIR__ . '/Notifications.i18n.php';

// Autoloader
$GLOBALS['wgAutoloadClasses']['Notifications'] = __DIR__ . '/Notifications.class.php';

$wgAutoloadClasses['BsNotificationsFormatter'] = __DIR__.'/includes/BsNotificationsFormatter.class.php';
$wgAutoloadClasses['BsEchoTextEmailFormatter'] = __DIR__.'/includes/BsEchoTextEmailFormatter.class.php';
$wgAutoloadClasses['BsEchoEmailSingle'] = __DIR__.'/includes/BsEchoEmailSingle.class.php';
$wgAutoloadClasses['BsEchoTextEmailDecorator'] = __DIR__.'/includes/BsEchoTextEmailDecorator.class.php';

$wgResourceModuleTemplate = array(
	'localBasePath' => "$IP/extensions/BlueSpiceExtensions/Notifications/resources",
	'remoteExtPath' => 'BlueSpiceExtensions/Notifications/resources'
);

$wgResourceModules['ext.bluespice.notifications'] = array(
	'styles' => 'bluespice.notifications.css',
)+$wgResourceModuleTemplate;
/**
 * Setting defaults for users
 * Webprefix: echo-subscriptions-web-
 * Emailprefix: echo-subscriptions-email-
 */
// Email
$wgDefaultUserOptions['echo-subscriptions-email-edit-user-talk'] = true;
$wgDefaultUserOptions['echo-subscriptions-email-shoutbox-cat'] = true;

// Web
$wgDefaultUserOptions['echo-subscriptions-web-shoutbox-cat'] = true;

// Change initial values from Echo extension
$wgEchoNotificationCategories['edit-user-talk']['no-dismiss'] = array();

// Email
$wgDefaultUserOptions['echo-subscriptions-email-mention'] = false;

// Web
$wgDefaultUserOptions['echo-subscriptions-web-article-linked'] = true;
$wgDefaultUserOptions['echo-subscriptions-web-mention'] = true;

//Overwrite, cause there is no hook
$wgEchoNotifiers['web'] = array( 'Notifications', 'notifyWithNotification' );

unset( $wgResourceModuleTemplate );