<?php
/**
 * Buildes URIs for ExtendedSearch for MediaWiki
 *
 * Part of BlueSpice for MediaWiki
 *
 * @author     Mathias Scheer <scheer@hallowelt.biz>
 * @author     Markus Glaser <glaser@hallowelt.biz>
 * @author     Stephan Muggli <muggli@hallowelt.biz>
 * @package    BlueSpice_Extensions
 * @subpackage ExtendedSearch
 * @copyright  Copyright (C) 2010 Hallo Welt! - Medienwerkstatt GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */
/* Changelog
 * v0.1
 * FIRST CHANGES
 */
/**
 * Buildes URIs for ExtendedSearch for MediaWiki
 * @package BlueSpice_Extensions
 * @subpackage ExtendedSearch
 */
class SearchUriBuilder {

	/**
	 * URL basis
	 */
	const BASE = 1;
	/**
	 * Search input
	 */
	const INPUT = 2;
	/**
	 * Scope: title or text
	 */
	const SCOPE = 4;
	/**
	 * Search files?
	 */
	const FILES = 8;
	/**
	 * Describes submit type
	 */
	const SUBMIT = 16;
	/**
	 * Which namespaces to search
	 */
	const NAMESPACES = 32;
	/**
	 * Is this a more like this search?
	 */
	const MLT = 64;
	/**
	 * Which categories to search
	 */
	const CATS = 128;
	/**
	 * Which filetypes to search
	 */
	const TYPE = 256;
	/**
	 * Which editors to search
	 */
	const EDITOR = 512;
	/**
	 * Order of search results
	 */
	const ORDER = 1024;
	/**
	 * Ascending or descending order?
	 */
	const ASC = 2048;
	/**
	 * Where to start.
	 */
	const OFFSET = 4096;
	/**
	 * Other params (?)
	 */
	const EXTENDED = 8192;
	/**
	 * Combination of order, direction and offset (?)
	 */
	const ORDER_ASC_OFFSET = 7168;
	/**
	 * Everything
	 */
	const ALL = 8191; // all but EXTENDED
	/**
	 * Other params (?)
	 */
	const NO_ENCODE = 16384;

	/**
	 * Currently determined search options.
	 * @var SearchOptions Currently determined search options.
	 */
	protected $oSearchOptions = null;
	/**
	 * Currently determined search request.
	 * @var SearchRequest Currently determined search request.
	 */
	protected $oSearchRequest = null;
	/**
	 * Parts of the URI to build
	 * @var array Key => Value pairs of URI parts
	 */
	protected $aUri = array();
	/**
	 * Store for already determined uri parts.
	 * @var array List of uri parts
	 */
	protected $aCache = array();
	/**
	 * Instance of search service
	 * @var object of search service
	 */
	protected static $oInstance = null;

	/**
	 * Constructor for SearchUriBuilderMW class
	 * @param SearchOptions $oSearchOptions SearchOptions object
	 */
	public function __construct() {
		$this->oSearchOptions = SearchOptions::getInstance();
		$this->oSearchRequest = SearchRequest::getInstance();

		$this->aUri[self::BASE] = SpecialPage::getTitleFor( 'SpecialExtendedSearch' )->getLocalUrl();
		$this->aUri[self::INPUT] = 'search_input='.$this->oSearchOptions->getOption( 'searchStringRaw' );
		$this->aUri[self::SCOPE] = 'search_scope='.$this->oSearchOptions->getOption( 'scope' );
		$this->aUri[self::FILES] = 'search_files='
				.( ( $this->oSearchOptions->getOption( 'files' ) === true ) ? '1' : '0' );
		$this->aUri[self::SUBMIT] = 'search_submit=1';

		$nss = $this->oSearchOptions->getOption( 'namespaces' );
		$namespaces = array();
		foreach ( $nss as $ns ) {
			$namespaces[] = 'na[]='.$ns;
		}
		if ( !empty( $namespaces ) )
				$this->aUri[self::NAMESPACES] = implode( '&', $namespaces );

		$aCategoriesFromOptions = $this->oSearchOptions->getOption( 'cats' );
		if ( !empty( $aCategoriesFromOptions ) )
				$this->aUri[self::CATS] = 'ca[]='.implode( '&ca[]=', $aCategoriesFromOptions );

		$aTypesFromOptions = $this->oSearchOptions->getOption( 'type' );
		if ( !empty( $aTypesFromOptions ) )
				$this->aUri[self::TYPE] = 'ty[]='.implode( '&ty[]=', $aTypesFromOptions );

		$aEditorsFromOptions = $this->oSearchOptions->getOption( 'editor' );
		if ( !empty( $aEditorsFromOptions ) )
				$this->aUri[self::EDITOR] = 'ed[]='.implode( '&ed[]=', $aEditorsFromOptions );

		$this->aUri[self::ORDER] = 'search_order='.$this->oSearchOptions->getOption( 'order' );
		$this->aUri[self::ASC] = 'search_asc='.$this->oSearchOptions->getOption( 'asc' );
		$this->aUri[self::OFFSET] = 'search_offset='.$this->oSearchOptions->getOption( 'offset' );
		$this->aUri[self::EXTENDED] = 'search_extended=1';
	}

	/**
	 * Return a instance of SearchUriBuilder.
	 * @return SearchRequest Instance of SearchUriBuilder
	 */
	public static function getInstance() {
		wfProfileIn( 'BS::'.__METHOD__ );
		if ( self::$oInstance === null ) {
			self::$oInstance = new self();
		}

		wfProfileOut( 'BS::'.__METHOD__ );
		return self::$oInstance;
	}

	/**
	 * Actually compiles an uri
	 * @param int $iInclude bitmask of class constants
	 * @param int $iExclude bitmask of class constants
	 * @return string uri that is NOT urlencoded 
	 */
	public function buildUri( $iInclude, $iExclude = 0 ) {
		$components = $iInclude & (~$iExclude);
		if ( isset( $this->aCache[$components] ) ) return $this->aCache[$components];

		$arrayKeysWanted = array();
		if ( self::INPUT & $components )      $arrayKeysWanted[self::INPUT] = true;
		if ( self::SCOPE & $components )      $arrayKeysWanted[self::SCOPE] = true;
		if ( self::FILES & $components )      $arrayKeysWanted[self::FILES] = true;
		if ( self::SUBMIT & $components )     $arrayKeysWanted[self::SUBMIT] = true;
		if ( self::NAMESPACES & $components ) $arrayKeysWanted[self::NAMESPACES] = true;
		if ( self::MLT & $components )        $arrayKeysWanted[self::MLT] = true;
		if ( self::CATS & $components )       $arrayKeysWanted[self::CATS] = true;
		if ( self::TYPE & $components )       $arrayKeysWanted[self::TYPE] = true;
		if ( self::EDITOR & $components )     $arrayKeysWanted[self::EDITOR] = true;
		if ( self::ORDER & $components )      $arrayKeysWanted[self::ORDER] = true;
		if ( self::ASC & $components )        $arrayKeysWanted[self::ASC] = true;
		if ( self::EXTENDED & $components )   $arrayKeysWanted[self::EXTENDED] = true;

		$arrayKeysValuesWanted = array_intersect_key( $this->aUri , $arrayKeysWanted );
		$sParams = implode( '&', $arrayKeysValuesWanted );

		if ( self::BASE & $components ) {
			$uri = $this->aUri[self::BASE];
			if ( !empty( $sParams ) ) {
				$uri .= ( strpos( $this->aUri[self::BASE], '?' ) === false ) ? '?' : '&';
				$uri .= $sParams;
			}
		} else $uri = $sParams;

		if ( $iExclude & self::NO_ENCODE ) {
			$uri = htmlspecialchars( $uri, ENT_QUOTES, 'UTF-8' );
		}

		$this->aCache[$components] = $uri;

		return $uri;
	}

}