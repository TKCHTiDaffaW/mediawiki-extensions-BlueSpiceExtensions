<?php
/**
 * Statistics Extension for BlueSpice
 *
 * Adds statistical analysis to pages.
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
 * @author     Markus Glaser <glaser@hallowelt.com>
 * @author     Tobias Weichart <weichart@hallowelt.com>
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @version    2.23.1
 * @package    BlueSpice_Extensions
 * @subpackage Statistics
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */

/**
 * Main class for Statistics extension
 * @package BlueSpice_Extensions
 * @subpackage Statistics
 */
class Statistics extends BsExtensionMW {

	/**
	 * Collects all available diagrams
	 * @var array List of strings
	 */
	protected static $aAvailableDiagramClasses = array();
	/**
	 * Contains all available diagrams
	 * @var array List of diagram objects
	 */
	protected static $aAvailableDiagrams = null;
	/**
	 * Contains all available filters
	 * @var array List of filter objects.
	 */
	protected static $aAvailableFilters = array();

	/**
	 * Initialization of Statistics extension
	 */
	protected function initExt() {
		wfProfileIn( 'BS::Statistics::initExt' );
		$this->setHook( 'ParserFirstCallInit' );
		$this->setHook( 'BeforePageDisplay' );
		$this->setHook( 'BSExtendedSearchAdminButtons' );
		$this->setHook( 'BSDashboardsAdminDashboardPortalConfig' );
		$this->setHook( 'BSDashboardsAdminDashboardPortalPortlets' );
		$this->setHook( 'BSDashboardsUserDashboardPortalConfig' );
		$this->setHook( 'BSDashboardsUserDashboardPortalPortlets' );
		$this->setHook( 'BSUserSidebarGlobalActionsWidgetGlobalActions' );
		$this->setHook( 'BSUsageTrackerRegisterCollectors' );

		BsConfig::registerVar( 'MW::Statistics::ExcludeUsers', array( 'WikiSysop' ), BsConfig::LEVEL_PUBLIC|BsConfig::TYPE_ARRAY_STRING, 'bs-statistics-pref-excludeusers', 'multiselectplusadd' );
		BsConfig::registerVar( 'MW::Statistics::MaxNumberOfIntervals', 366, BsConfig::LEVEL_PUBLIC|BsConfig::TYPE_INT, 'bs-statistics-pref-maxnumberofintervals', 'int' );

		$aAvailableGrains = array(
			'Y' => 'bs-statistics-year',
			'm' => 'bs-statistics-month',
			'W' => 'bs-statistics-week',
			'd' => 'bs-statistics-day',
		);
		BsConfig::registerVar( 'MW::Statistics::AvailableGrains', $aAvailableGrains, BsConfig::LEVEL_PRIVATE|BsConfig::TYPE_ARRAY_MIXED, 'bs-statistics-pref-AvailableGrains');

		Statistics::addAvailableFilter( 'FilterUsers' );
		Statistics::addAvailableFilter( 'FilterNamespace' );
		Statistics::addAvailableFilter( 'FilterCategory' );
		Statistics::addAvailableFilter( 'FilterSearchScope' );

		Statistics::addAvailableDiagramClass( 'BsDiagramNumberOfUsers' );
		Statistics::addAvailableDiagramClass( 'BsDiagramNumberOfPages' );
		Statistics::addAvailableDiagramClass( 'BsDiagramNumberOfArticles' );
		Statistics::addAvailableDiagramClass( 'BsDiagramNumberOfEdits' );
		Statistics::addAvailableDiagramClass( 'BsDiagramEditsPerUser' );
		Statistics::addAvailableDiagramClass( 'BsDiagramSearches' );

		wfProfileOut( 'BS::Statistics::initExt' );
	}

	/**
	 * Registers available diagrams
	 * @param string $sDiagramClass Name of class.
	 */
	public static function addAvailableDiagramClass( $sDiagramClass ) {
		if ( strpos( $sDiagramClass, 'Bs' ) !== 0 ) {
			$sDiagramClassName =  'Bs'.$sDiagramClass;
		} else {
			$sDiagramClassName =  $sDiagramClass;
		}

		Statistics::$aAvailableDiagramClasses[$sDiagramClassName] = $sDiagramClassName;
	}

	/**
	 * Returns list of available diagrams.
	 * @return array List of diagram objects.
	 */
	public static function getAvailableDiagrams() {
		self::loadAvailableDiagrams();
		return Statistics::$aAvailableDiagrams;
	}

	/**
	 * Loads all available diagrams, i.e. instanciate all classes
	 * @return array List of available diagrams
	 */
	protected static function loadAvailableDiagrams() {
		if ( !is_null( self::$aAvailableDiagrams ) ) {
			return self::$aAvailableDiagrams;
		}
		self::$aAvailableDiagrams = array();
		foreach ( Statistics::$aAvailableDiagramClasses as $sDiagramClass ) {
			self::$aAvailableDiagrams[$sDiagramClass] = new $sDiagramClass();
		}
		return self::$aAvailableDiagrams;
	}

	/**
	 * Get instance for a particluar diagram class.
	 * @param string $sDiagramClass Name of diagram
	 * @return BsDiagram
	 */
	public static function getDiagram( $sDiagramClass ) {
		self::loadAvailableDiagrams();
		return Statistics::$aAvailableDiagrams[$sDiagramClass];
	}

	/**
	 * Registers a filter
	 * @param string $sFilterClass Name of filter class
	 */
	public static function addAvailableFilter( $sFilterClass ) {
		if ( strpos( $sFilterClass, 'Bs' ) !== 0 ) {
			$sFilterClassName =  'Bs'.$sFilterClass;
		} else {
			$sFilterClassName =  $sFilterClass;
		}
	}

	/**
	 * Returns list of available filters
	 * @return array Names of filtesr.
	 */
	public static function getAvailableFilters() {
		return Statistics::$aAvailableFilters;
	}

	/**
	 * Get a particular filter
	 * @param string $sFilterClass Name of filter
	 * @return BsStatisticsFilter Filter object
	 */
	public static function getFilter( $sFilterClass ) {
		if ( isset( Statistics::$aFilterDiagrams[$sFilterClass] ) ) {
			return Statistics::$aFilterDiagrams[$sFilterClass];
		} else {
			return null;
		}
	}

	/**
	 * Registers a tag "bs:infobox" with the parser. for legacy reasons witn HalloWiki, also "infobox" is supported. Called by ParserFirstCallInit hook
	 * @param Parser $parser MediaWiki parser object
	 * @return bool allow other hooked methods to be executed. always true
	 */
	public function onParserFirstCallInit( &$parser ) {
		// for legacy reasons
		$parser->setHook( 'bs:statistics:progress', array( $this, 'onTagProgress' ) );
		return true;
	}

	/**
	 *
	 * @param OutputPage $oOutputPage
	 * @param Skin $oSkin
	 */
	public function onBeforePageDisplay( &$oOutputPage, &$oSkin ) {
		$oOutputPage->addModuleStyles( 'ext.bluespice.statistics.styles' );

		if( !$oSkin->getTitle()->equals(SpecialPage::getTitleFor('AdminDashboard'))
			&& !$oSkin->getTitle()->equals(SpecialPage::getTitleFor('WikiAdmin'))
			&& !$oSkin->getTitle()->equals(SpecialPage::getTitleFor('UserDashboard'))
		) return true;

		$oOutputPage->addModules('ext.bluespice.statisticsPortlets');
		return true;
	}

	/**
	 * Renders the Progress tag. Called by parser function.
	 * @param string $input Inner HTML of InfoBox tag. Not used.
	 * @param array $args List of tag attributes.
	 * @param Parser $parser MediaWiki parser object
	 * @return string HTML output that is to be displayed.
	 */
	public function onTagProgress( $input, $args, $parser ) {
		$parser->getOutput()->setProperty( 'bs-tag-statistics-progress', 1 );

		$iBaseCount = BsCore::sanitizeArrayEntry( $args, 'basecount'     , 100  , BsPARAMTYPE::INT );
		$sBaseItem  = BsCore::sanitizeArrayEntry( $args, 'baseitem'      , '' , BsPARAMTYPE::STRING );
		$sFraction  = BsCore::sanitizeArrayEntry( $args, 'progressitem'  , 'OK' , BsPARAMTYPE::STRING );
		$iWidth     = BsCore::sanitizeArrayEntry( $args, 'width'         , 100  , BsPARAMTYPE::INT );

		// no Article when in cli mode
		if ( !is_object( $this->getTitle() ) ) {
			return '';
		}

		$sText = BsPageContentProvider::getInstance()->getContentFromTitle( $this->getTitle() );

		// substract 1 because one item is in the progressitem attribute
		$iFraction = substr_count( $sText, $sFraction ) - 1;

		if ( $sBaseItem ) {
			$iBase = substr_count( $sText, $sBaseItem ) - 1;
		} else {
			$iBase = $iBaseCount;
		}

		$fPercent = $iFraction / $iBase;

		$iWidthGreen = floor($iWidth * $fPercent);
		$iWidthRemain = $iWidth-$iWidthGreen;

		$sPercent = sprintf( "%0.1f", $fPercent * 100 );

		$sOut = '<div style="background-color:green;border:2px solid #DDDDDD;width:'.$iWidthGreen.'px;height:25px;float:left;color:#DDDDDD;text-align:center;border-right:0px;text-weight:bold;vertical-align:middle;">'.$sPercent.'%</div>';
		$sOut .= '<div style="border:2px solid #DDDDDD;border-left:0px;width:'.$iWidthRemain.'px;height:25px;float:left;"></div>';

		return $sOut;
	}

	public function onBSExtendedSearchAdminButtons( $oSpecialPage, &$aSearchAdminButtons ) {
		global $wgScriptPath;

		$aSearchAdminButtons['Statistics'] = array(
			'href' => SpecialPage::getTitleFor( 'ExtendedStatistics' )->getLinkUrl(),
			'onclick' => '',
			'label' => wfMessage( 'bs-extendedsearch-statistics' )->plain(),
			'image' => "$wgScriptPath/extensions/BlueSpiceExtensions/Statistics/resources/images/bs-searchstatistics.png"
		);

		return true;
	}

	/**
	 * Hook Handler for BSDashboardsAdminDashboardPortalConfig
	 *
	 * @param object $oCaller caller instance
	 * @param array &$aPortalConfig reference to array portlet configs
	 * @param boolean $bIsDefault default
	 * @return boolean always true to keep hook alive
	 */
	public function onBSDashboardsAdminDashboardPortalConfig( $oCaller, &$aPortalConfig, $bIsDefault ) {
		$this->getPortalConfig( $aPortalConfig );

		return true;
	}

	/**
	 * Hook Handler for BSDashboardsAdminDashboardPortalPortlets
	 *
	 * @param array &$aPortlets reference to array portlets
	 * @return boolean always true to keep hook alive
	 */
	public function onBSDashboardsAdminDashboardPortalPortlets( &$aPortlets ) {
		$this->getPortalPortlets( $aPortlets );

		return true;
	}

		/**
	 * Hook Handler for BSDashboardsAdminDashboardPortalConfig
	 *
	 * @param object $oCaller caller instance
	 * @param array &$aPortalConfig reference to array portlet configs
	 * @param boolean $bIsDefault default
	 * @return boolean always true to keep hook alive
	 */
	public function onBSDashboardsUserDashboardPortalConfig( $oCaller, &$aPortalConfig, $bIsDefault ) {
		$this->getPortalConfig( $aPortalConfig );

		return true;
	}

	/**
	 * Hook Handler for BSDashboardsAdminDashboardPortalPortlets
	 *
	 * @param array &$aPortlets reference to array portlets
	 * @return boolean always true to keep hook alive
	 */
	public function onBSDashboardsUserDashboardPortalPortlets( &$aPortlets ) {
		$this->getPortalPortlets( $aPortlets );

		return true;
	}

	public function getPortalConfig( &$aPortalConfig ) {
		$aPortalConfig[1][] = array(
			'type'  => 'BS.Statistics.StatisticsPortletNumberOfUsers',
			'config' => array(
				'title' => wfMessage( 'bs-statistics-portlet-numberofusers' )->plain(),
				'inputPeriod' => 'week',
			)
		);
		$aPortalConfig[1][] = array(
			'type'  => 'BS.Statistics.StatisticsPortletNumberOfEdits',
			'config' => array(
				'title' => wfMessage( 'bs-statistics-portlet-numberofedits' )->plain(),
				'inputPeriod' => 'week',
			)
		);
		$aPortalConfig[2][] = array(
			'type'  => 'BS.Statistics.StatisticsPortletNumberOfArticles',
			'config' => array(
				'title' => wfMessage( 'bs-statistics-portlet-numberofpages' )->plain(),
				'inputPeriod' => 'week',
			)
		);
		$aPortalConfig[2][] = array(
			'type'  => 'BS.Statistics.StatisticsPortletNumberOfPages',
			'config' => array(
				'title' => wfMessage( 'bs-statistics-portlet-numberofpages' )->plain(),
				'inputPeriod' => 'week',
			)
		);

		return true;
	}

	public function getPortalPortlets( &$aPortlets ) {
		$aPortlets[] = array(
			'type'  => 'BS.Statistics.StatisticsPortletNumberOfUsers',
			'config' => array(
				'title' => wfMessage( 'bs-statistics-portlet-numberofusers' )->plain(),
				'inputPeriod' => 'week',
			),
			'title' => wfMessage( 'bs-statistics-portlet-numberofusers' )->plain(),
			'description' => wfMessage( 'bs-statistics-portlet-numberofusersdesc' )->plain()
		);
		$aPortlets[] = array(
			'type'  => 'BS.Statistics.StatisticsPortletNumberOfEdits',
			'config' => array(
				'title' => wfMessage( 'bs-statistics-portlet-numberofedits' )->plain(),
				'inputPeriod' => 'week',
			),
			'title' => wfMessage( 'bs-statistics-portlet-numberofedits' )->plain(),
			'description' => wfMessage( 'bs-statistics-portlet-numberofeditsdesc' )->plain()
		);
		$aPortlets[] = array(
			'type'  => 'BS.Statistics.StatisticsPortletNumberOfArticles',
			'config' => array(
				'title' => wfMessage( 'bs-statistics-portlet-numberofpages' )->plain(),
				'inputPeriod' => 'week',
			),
			'title' => wfMessage( 'bs-statistics-portlet-numberofpages' )->plain(),
			'description' => wfMessage( 'bs-statistics-portlet-numberofpagesdesc' )->plain()
		);
		$aPortlets[] = array(
			'type'  => 'BS.Statistics.StatisticsPortletNumberOfPages',
			'config' => array(
				'title' => wfMessage( 'bs-statistics-portlet-numberofpages' )->plain(),
				'inputPeriod' => 'week',
			),
			'title' => wfMessage( 'bs-statistics-portlet-numberofpages' )->plain(),
			'description' => wfMessage( 'bs-statistics-portlet-numberofpagesdesc' )->plain()
		);

		return true;
	}

	/**
	 * Adds Special:ExtendedStatistic link to wiki wide widget
	 * @param UserSidebar $oUserSidebar
	 * @param User $oUser
	 * @param array $aLinks
	 * @param string $sWidgetTitle
	 * @return boolean
	 */
	public function onBSUserSidebarGlobalActionsWidgetGlobalActions( UserSidebar $oUserSidebar, User $oUser, &$aLinks, &$sWidgetTitle ) {
		$oSpecialExtendedStatistic = SpecialPageFactory::getPage(
			'ExtendedStatistics'
		);
		if( !$oSpecialExtendedStatistic ) {
			return true;
		}
		$aLinks[] = array(
			'target' => $oSpecialExtendedStatistic->getPageTitle(),
			'text' => $oSpecialExtendedStatistic->getDescription(),
			'attr' => array(),
			'position' => 700,
			'permissions' => array( 'read' ),
		);
		return true;
	}

	/**
	 * Register tag with UsageTracker extension
	 * @param array $aCollectorsConfig
	 * @return Always true to keep hook running
	 */
	public function onBSUsageTrackerRegisterCollectors( &$aCollectorsConfig ) {
		$aCollectorsConfig['bs:statistics:progress'] = array(
			'class' => 'Property',
			'config' => array(
				'identifier' => 'bs-tag-statistics-progress'
			)
		);
		return true;
	}
}
