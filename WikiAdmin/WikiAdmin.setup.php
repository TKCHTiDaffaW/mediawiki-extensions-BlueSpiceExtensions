<?php

BsExtensionManager::registerExtension('WikiAdmin', BsRUNLEVEL::FULL|BsRUNLEVEL::REMOTE, BsACTION::LOAD_SPECIALPAGE);

$wgExtensionFunctions[] = 'WikiAdmin::loadModules';

$wgMessagesDirs['WikiAdmin'] = __DIR__ . '/i18n';

$wgExtensionMessagesFiles['WikiAdmin'] = __DIR__ . '/languages/WikiAdmin.i18n.php';

// Specialpage and messages
$wgAutoloadClasses['SpecialWikiAdmin'] = __DIR__ . '/includes/specials/SpecialWikiAdmin.class.php';
$wgSpecialPageGroups['SpecialWikiAdmin'] = 'bluespice';
$wgExtensionMessagesFiles['WikiAdminAlias'] = __DIR__ . '/includes/specials/SpecialWikiAdmin.alias.php';
$wgSpecialPages['SpecialWikiAdmin'] = 'SpecialWikiAdmin';

$wgHooks['SkinTemplateOutputPageBeforeExec'][] = 'WikiAdmin::onSkinTemplateOutputPageBeforeExec';