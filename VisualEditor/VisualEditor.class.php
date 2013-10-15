<?php

/**
 * Visual Editor extension for BlueSpice
 *
 * Visual editor for MediaWiki.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * 
 * This file is part of BlueSpice for MediaWiki
 * For further information visit http://www.blue-spice.org
 *
 * @author     Markus Glaser <glaser@hallowelt.biz>
 * @author     Sebastian Ulbricht
 * @version    1.22.0 stable
 * @package    BlueSpice_Extensions
 * @subpackage VisualEditor
 * @copyright  Copyright (C) 2011 Hallo Welt! - Medienwerkstatt GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */

/**
 * Base class for VisualEditor extension
 * @package BlueSpice_Extensions
 * @subpackage VisualEditor
 */
class VisualEditor extends BsExtensionMW {

	private $bStartEditor = true;

	/**
	 * Stores whether available tag names in mediawiki have been collected. 
	 * This should only happen once, however, the hook is called more often.
	 * @var bool Availabel tags have been collected.
	 */
	private $bTagsCollected	 = false;
	/*
	 * Standard configuration for visual editor in full mode
	 */
	private $aConfigStandard = array (
		'selector'					 => 'textarea',
		'plugins'					 => array (
			"lists",
			//"emoticons",
			"table",
			"visualchars",
			"save",
			"searchreplace",
			"paste",
			//"spellchecker",
			"fullscreen",
			"textcolor",
			"contextmenu",
			//"link" //Needed for "unlink"
		),
		'external_plugins'			 => array (
			'bswikicode'	 => '../tiny_mce_plugins/bswikicode/plugin.js',
			'bsbehaviour'	 => '../tiny_mce_plugins/bsbehaviour/plugin.js',
			'bsactions'		 => '../tiny_mce_plugins/bsactions/plugin.js'
		),
		'menubar'					 => false,
		'statusbar'					 => false,
		//'inline'					 => true,
		'menu'						 => false,
		'toolbar1'					 => array (
			'bswiki', /*'bsswitch', */'save', '|', 'undo', 'redo', '|',
			'search', 'replace', 'paste', 'pasteword', 'selectall', '|',
			'bold', 'italic', 'underline', 'strikethrough', '|',
			'alignleft', 'aligncenter', 'alignright', 'alignjustify', '|',
			'bullist', 'numlist', '|', 'outdent', 'indent', '|',
			'styleselect', 'forecolor'
		),
		'toolbar2'					 => array (
			'hr', 'removeformat', '|', 'subscript', 'superscript', '|',
			'charmap', '|', 'table' ,'bstableaddrowbefore' ,'bstableaddrowafter' ,'bstabledeleterow' ,
			'|', 'bssignature', 'unlink',
			'bslinebreak', '|', 'fullscreen'
		),
		// autofocus on the editor instance with this id
		'auto_focus'				 => 'wpTextbox1',
		// the default text direction for the editor
		'directionality'			 => 'ltr',
		// use the browser spellcheck?
		'browser_spellcheck'		 => true,
		// default language
		'language'					 => 'en',
		// don't wrap the editable element?
		'nowrap'					 => false,
		// enable resizing for element like images, tables or media objects
		'object_resizing'			 => false,
		// convert font tags into spans with styles
		'convert_fonts_to_spans'	 => true,
		// the html mode for tag creation (we need xhtml)
		'element_format'			 => 'xhtml',
		// define the element what all inline elements needs to be wrapped in
		'forced_root_block'			 => 'p',
		// define the available block styles in the dropdown
		'block_formats'				 => array (
			'Paragraph'		 => 'p',
			'Preformated'	 => 'pre',
			'Heading 1'		 => 'h2',
			'Heading 2'		 => 'h3',
			'Heading 3'		 => 'h4',
			'Heading 4'		 => 'h5',
			'Heading 5'		 => 'h6'
		),
		// keep current style on pressing return
		'keep_styles'				 => true,
		// save plugin
		'save_enablewhendirty'		 => true,
		//Allow style tags in body and unordered lists in spans (inline)
		'valid_children' => "+body[style],+span[ul]",
		//set the id of the body tag in iframe to bodyContent, so styles do 
		//apply in a correct manner. This may be dangerous.
		'body_id' => 'bodyContent'
	);

	/**
	 * Default value for config of reduced version of the editor, which is currently stored in a private variable.
	 * @var array will be JSON encoded later for configuration. 
	 */
	private $aConfigOverwrite	 = array (
		'toolbar1'	 => array (
			'bswiki', 'bsswitch', 'save', '|', 'undo', 'redo', '|',
			'bold', 'italic', 'underline', 'strikethrough', '|',
			'alignleft', 'aligncenter', 'alignright', 'alignjustify'
		),
		'toolbar2'	 => array (
			'bssignature', 'bslink', 'unlink',
			'bscategory', 'bschecklist', 'bslinebreak', '|', 'fullscreen'
		)
	);
	private $aMergeToString		 = array (
		'plugins', 'toolbar1', 'toolbar2'
	);
	
	protected $bShowToolbarIcon = true;

	/**
	 * Constructor of VisualEditor class
	 */
	public function __construct() {
		wfProfileIn( 'BS::' . __METHOD__ );
		// Base settings
		$this->mExtensionFile    = __FILE__;
		$this->mExtensionType    = EXTTYPE::VARIABLE;
		$this->mInfo             = array (
			EXTINFO::NAME        => 'VisualEditor',
			EXTINFO::DESCRIPTION => 'Visual editor for MediaWiki.',
			EXTINFO::AUTHOR      => 'Markus Glaser, Sebastian Ulbricht',
			EXTINFO::VERSION     => '1.22.0',
			EXTINFO::STATUS      => 'beta',
			EXTINFO::URL         => 'http://www.hallowelt.biz',
			EXTINFO::DEPS        => array ( 'bluespice' => '1.22.0' )
		);
		$this->mExtensionKey	 = 'MW::VisualEditor';

		BsConfig::registerVar( 'MW::VisualEditor::disableNS', array ( NS_MEDIAWIKI ),
			BsConfig::LEVEL_PUBLIC | BsConfig::TYPE_ARRAY_INT | BsConfig::USE_PLUGIN_FOR_PREFS,
						 'bs-visualeditor-pref-disableNS', 'multiselectex' );
		BsConfig::registerVar( 'MW::VisualEditor::defaultNoContextNS',
			array ( NS_SPECIAL, NS_MEDIA, NS_FILE ),
			BsConfig::LEVEL_PRIVATE | BsConfig::TYPE_ARRAY_INT,
			'bs-visualeditor-pref-defaultNoContextNS', 'multiselectex' );

		BsConfig::registerVar( 'MW::VisualEditor::SpecialTags', array ( ),
			BsConfig::LEVEL_PRIVATE | BsConfig::RENDER_AS_JAVASCRIPT | BsConfig::TYPE_BOOL,
			'bs-visualeditor-pref-SpecialTags' );
		BsConfig::registerVar( 'MW::VisualEditor::AllowedTags', array ( ),
			BsConfig::LEVEL_PRIVATE | BsConfig::RENDER_AS_JAVASCRIPT | BsConfig::TYPE_BOOL,
			'bs-visualeditor-pref-AllowedTags' );

		BsConfig::registerVar( 'MW::VisualEditor::Use', true,
			BsConfig::LEVEL_USER | BsConfig::RENDER_AS_JAVASCRIPT | BsConfig::TYPE_BOOL,
			'bs-visualeditor-pref-Use', 'toggle' );
		BsConfig::registerVar( 'MW::VisualEditor::UseLimited', false,
			BsConfig::LEVEL_USER | BsConfig::RENDER_AS_JAVASCRIPT | BsConfig::TYPE_BOOL,
			'bs-visualeditor-pref-UseLimited', 'toggle' );
		BsConfig::registerVar( 'MW::VisualEditor::UseForceLimited', false,
			BsConfig::LEVEL_PUBLIC | BsConfig::RENDER_AS_JAVASCRIPT | BsConfig::TYPE_BOOL,
			'bs-visualeditor-pref-UseForceLimited', 'toggle' );

		BsConfig::registerVar( 'MW::VisualEditor::DebugMode', false,
			BsConfig::LEVEL_PRIVATE | BsConfig::RENDER_AS_JAVASCRIPT | BsConfig::TYPE_BOOL,
			'bs-visualeditor-pref-DebugMode' );
		BsConfig::registerVar( 'MW::VisualEditor::GuiMode', true,
			BsConfig::LEVEL_PRIVATE | BsConfig::RENDER_AS_JAVASCRIPT | BsConfig::TYPE_BOOL,
			'bs-visualeditor-pref-GuiMode' );
		BsConfig::registerVar( 'MW::VisualEditor::GuiSwitchable', true,
			BsConfig::LEVEL_PRIVATE | BsConfig::RENDER_AS_JAVASCRIPT | BsConfig::TYPE_BOOL,
			'bs-visualeditor-pref-GuiSwitchable' );

		wfProfileOut( 'BS::' . __METHOD__ );
	}

	/**
	 * Constructor of VisualEditor class
	 */
	protected function initExt() {
		$this->mCore->registerBehaviorSwitch(
			'NOEDITOR', array ( $this, 'noEditorCallback' )
		);

		// Hooks
		$this->setHook( 'ParserAfterTidy' );
		$this->setHook( 'BeforePageDisplay' );
		$this->setHook( 'ResourceLoaderGetConfigVars' );
		$this->setHook( 'BSExtendedEditBarBeforeEditToolbar' );
	}

	/**
	 * 
	 * @global Language $wgLang
	 * @global Parser $wgParser
	 * @global OutputPage $wgOut
	 * @param array $vars
	 * @return boolean
	 */
	public function onResourceLoaderGetConfigVars( &$vars ) {
		if ( !BsConfig::get( 'MW::VisualEditor::Use' ) || !$this->bStartEditor ) {
			$vars[ 'BsVisualEditorUseTidy' ] = false;
		} else {
			$vars[ 'BsVisualEditorUseTidy' ] = true;
		}

		return true;
	}
	
	public function onBSExtendedEditBarBeforeEditToolbar( &$aRows, &$aButtonCfgs ) {
		if( $this->bShowToolbarIcon == false ) return true;

		$aRows[0]['editing'][10] = 'bs-editbutton-visualeditor';

		$aButtonCfgs['bs-editbutton-visualeditor'] = array(
			'tip' => wfMessage( 'bs-visualeditor-editbutton-hint' )->plain()
		);
		return true;
	}

	/**
	 * Compiles a list of tags that must be passed by the editor.
	 * @global Language $wgLang
	 * @global string $wgScriptPath
	 * @global string $wgStylePath
	 * @param Parser $oParser MediaWiki parser object.
	 * @return bool Allow other hooked methods to be executed. Always true.
	 */
	public function onParserAfterTidy( &$oParser ) {
		global $wgLang, $wgScriptPath, $wgDefaultSkin;

		if ( $this->bTagsCollected ) return true;
		$this->bTagsCollected = true;

		$tags		 = $oParser->getTags();
		$allowedTags = '';
		$specialTags = '';
		foreach ( $tags as $tag ) {
			if ( $tag == 'pre' ) continue;
			$allowedTags .= $tag . '[*],';
			$specialTags .= $tag . '|';
		}

		BsConfig::set( 'MW::VisualEditor::SpecialTags', $specialTags );
		BsConfig::set( 'MW::VisualEditor::AllowedTags', $allowedTags );

		//TODO: There are duplicates!
		$aDefaultTags = array (
			"syntaxhighlight", "source", "infobox", "categorytree", "nowiki",
			"presentation", "includeonly", "onlyinclude", "noinclude",
			"backlink", "gallery", "math", "video", "rss", "tagcloud"
		);
		$this->aConfigStandard[ "specialtaglist" ] =
				BsConfig::get( 'MW::VisualEditor::SpecialTags' )
				. implode( '|', $aDefaultTags );

		$this->aConfigStandard[ "extended_valid_elements" ] =
				BsConfig::get( 'MW::VisualEditor::AllowedTags' )
				. implode( '[*]|', $aDefaultTags ) . '[*]|body[style]';

		// find the right language file
		$language	 = $wgLang->getCode();
		$langDir	 = dirname( __FILE__ ) . '/resources/tinymce/langs';
		if ( !file_exists( "{$langDir}/{$language}.js" ) ) {
			$language = explode( '_', $language );
			if ( file_exists( "{$langDir}/{$language[ 0 ]}.js" ) ) {
				$language = $language[ 0 ];
			} else {
				$language = 'en';
			}
		}
		$this->aConfigStandard[ 'language' ] = $language;

		// TODO SW: use string flag as parameter to allow hookhandler to 
		// determin context. This will be usefull if hook gets called in 
		// another place
		wfRunHooks( 'VisualEditorConfig',
			  array ( &$this->aConfigStandard, &$this->aConfigOverwrite ) );

		$this->aConfigStandard	 = $this->_prepareConfig( $this->aConfigStandard );
		$this->aConfigOverwrite	 = $this->_prepareConfig( $this->aConfigOverwrite );

		global $wgOut;

		$wgOut->addJsConfigVars( 'BsVisualEditorConfigDefault', $this->aConfigStandard );
		$wgOut->addJsConfigVars( 'BsVisualEditorConfigAlternative',
						   array_merge(
						$this->aConfigStandard, $this->aConfigOverwrite
				)
		);

		return true;
	}

	protected function _prepareConfig( $config ) {
		$tmp = array ( );

		foreach ( $config as $key => $value ) {
			if ( in_array( $key, $this->aMergeToString ) ) {
				$tmp[ $key ] = join( ' ', $value );
			} else {
				$tmp[ $key ] = $value;
			}
		}

		return $tmp;
	}

	/*
	 * Adds module
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return boolean Always true
	 */
	public function onBeforePageDisplay( $out, $skin ) {

		if ( $this->checkContext( $out->getTitle() ) === false ) {
			$this->noEditorCallback();
			return true;
		}

		$sAction = $out->getRequest()->getVal( 'action', 'view' );
		if ( $sAction != 'edit' && $sAction != 'preview' && $sAction != 'submit' ) return true;

		$out->addModuleStyles( 'ext.bluespice.visualEditor.styles' );
		$out->addModules( 'ext.bluespice.visualEditor' );

		return true;
	}

	/**
	 * Callback function in case __NOEDITOR__ keyword is found. Basically removes toggle button
	 */
	public function noEditorCallback() {
		$this->bShowToolbarIcon = false;

		$this->bStartEditor = false;
		//Overwrite user setting
		BsCore::registerClientScriptBlock(
				$this->mExtensionKey, "bsVisualEditorUse=false;", 'NOEDITOR' );
		BsConfig::set( 'MW::VisualEditor::Use', false, true ); //This seems to be too late
	}

	/**
	 * 
	 * @global User $wgUser
	 * @global Language $wgLang
	 * @return string
	 */
	public static function doSaveArticle() {
		if ( BsCore::checkAccessAdmission( 'read' ) === false ) return true;
		global $wgLang, $wgRequest;
		$sArticleId = $wgRequest->getInt( 'articleId', -1 );
		$sText      = $wgRequest->getVal( 'text', '' );
		$sPageName  = $wgRequest->getVal( 'pageName', '' );
		$sSummary   = $wgRequest->getVal( 'summary', '' );
		$iSection   = $wgRequest->getInt( 'editsection', 0 );

		$sReturnEditTime = wfTimestampNow();
		if ( $sSummary == 'false' ) {
			$sSummary = wfMessage( 'bs-visualeditor-no-summary' )->plain();
		}

		if ( $sArticleId == -1 ) {
			$oArticle = new Article( Title::newFromText( $sPageName ) ); //Article::newFromTitle( Title::newFromText( $sPageName ) );
		} else {
			$oArticle = Article::newFromID( $sArticleId );
		}

		if ( $iSection ) {
			$sText = $oArticle->replaceSection( $iSection, $sText );
		}

		$oSaveResult = $oArticle->doEdit( $sText, $sSummary );

		$sTime    = $wgLang->timeanddate( $sReturnEditTime, true );
		$sMessage = '';
		$sResult  = '';
		if ( empty( $oSaveResult->errors ) ) {
			$sResult  = 'ok';
			$sMessage = wfMessage( 'bs-visualeditor-save-message', $sTime, $sSummary )->plain();
		} else {
			$sResult  = 'fail';
			$sMessage = $oSaveResult->getMessage();
		}

		$aOutput = array (
			'saveresult' => $sResult, //$oSaveResult->getMessage(),//$sSaveResultCode,
			'message'    => $sMessage, //wfMessage( 'bs-visualeditor-save-message', $sTime, $sSummary )->plain(),
			'edittime'   => $sReturnEditTime,
			'summary'    => $sSummary,
			'starttime'  => wfTimestamp( TS_MW, time() + 2 )
		);

		return FormatJson::encode( $aOutput );
	}

	public static function checkLinks( $links ) {
		$aResult = array ();
		foreach ( $links as $sTitle ) {
			$oTitle    = Title::newFromText( urldecode( $sTitle ) );
			$aResult[] = $oTitle instanceof Title ? $oTitle->exists() : false;
		}
		return FormatJson::encode( $aResult );
	}

	/**
	 * Sets parameters for more complex options in preferences
	 * @param string $sAdapterName Name of the adapter, e.g. MW
	 * @param BsConfig $oVariable Instance of variable
	 * @return array Preferences options
	 */
	public function runPreferencePlugin( $sAdapterName, $oVariable ) {
		wfProfileIn( 'BS::' . __METHOD__ );
		$aPrefs = array ( );

		switch ( $oVariable->getName() ) {
			case 'disableNS':
				global $wgContLang;
				$aExcludeNmsps = BsConfig::get( 'MW::VisualEditor::defaultNoContextNS' );
				foreach ( $wgContLang->getNamespaces() as $sNamespace ) {
					$iNsIndex			 = $wgContLang->getNsIndex( $sNamespace );
					if ( !MWNamespace::isTalk( $iNsIndex ) ) continue;
					$aExcludeNmsps[ ]	 = $iNsIndex;
				}
				$aPrefs[ 'type' ]	 = 'multiselectex';
				$aPrefs[ 'options' ] = BsNamespaceHelper::getNamespacesForSelectOptions( $aExcludeNmsps );
				break;
			default:
		}

		wfProfileOut( 'BS::' . __METHOD__ );
		return $aPrefs;
	}

	/**
	 * Checks wether to set Context or not.
	 * @param Title $oTitle
	 * @return bool
	 */
	private function checkContext( $oTitle ) {
		if ( !is_object( $oTitle ) ) return false;

		global $wgRequest;
		if ( $wgRequest->getVal( 'action' ) !== 'edit' && $wgRequest->getVal( 'action' ) !== 'submit' )
				return false;

		if ( !$oTitle->userCan( 'edit' ) ) return false;

		$aExcludeNmsps = BsConfig::get( 'MW::VisualEditor::defaultNoContextNS' );
		$aExcludeNmsps = array_merge( 
			$aExcludeNmsps, BsConfig::get( 'MW::VisualEditor::disableNS' )
		);
		if ( in_array( $oTitle->getNamespace(), $aExcludeNmsps ) ) return false;

		return true;
	}

}