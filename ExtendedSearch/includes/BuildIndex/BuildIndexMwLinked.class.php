<?php
/**
 * Controls linked files index building mechanism for ExtendedSearch for MediaWiki
 *
 * Part of BlueSpice for MediaWiki
 *
 * @author     Mathias Scheer <scheer@hallowelt.biz>
 * @author     Markus Glaser <glaser@hallowelt.biz>
 * @package    BlueSpice_Extensions
 * @subpackage ExtendedSearch
 * @copyright  Copyright (C) 2010 Hallo Welt! - Medienwerkstatt GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */
/* Changelog
 * v0.1
 * - initial commit
 */
/**
 * Controls linked files index building mechanism for ExtendedSearch for MediaWiki
 * @package BlueSpice_Extensions
 * @subpackage ExtendedSearch
 */
class BuildIndexMwLinked extends AbstractBuildIndexLinked {

	/**
	 * I18n key for general error message
	 */
	const S_ERROR_MSG_KEY = 'error-indexing-linked';
	/**
	 * Pointer to current database connection
	 * @var object Referenec to Database object 
	 */
	protected $oDbr;
	/**
	 * List of documents to be indexed.
	 * @var resource Result of db query.
	 */
	protected $documentsDb = null;
	/**
	 * Constructor for BuildIndexMwLinked class
	 * @param BsBuildIndexMainControl $oBsBuildIndexMainControl Instance to decorate. 
	 */
	public function __construct( $oMainControl ) {
		$this->oDbr = wfGetDB( DB_SLAVE );
		parent::__construct( $oMainControl );
	}

	/**
	 * Prepares list of documents to be indexed for wikis.
	 * @return int Number of documents to be indexed.
	 */
	public function crawl( $iArticleID = null ) {
		$this->writeLog();

		$tables = 'externallinks';
		$fields = 'DISTINCT(el_to)';
		$clauses = array( 'el_to LIKE \'file://%\'' );
		if ( $iArticleID != null && is_int( $iArticleID ) ) {
			$clauses[] = 'el_from = ' . $iArticleID;
		}
		$this->documentsDb = $this->oDbr->select( $tables, $fields, $clauses, __METHOD__ );

		$this->totalNoDocumentsCrawled = $this->oDbr->numRows( $this->documentsDb );
		return $this->totalNoDocumentsCrawled;
	}

	/**
	 * Creates a document object that can be indexed by Solr.
	 * @param string $type Of type 'wiki', 'doc', 'ppt', 'xls', 'pdf', 'txt', 'html', 'sql', 'sh', ...
	 * @param string $filename Filename of document to be indexed.
	 * @param string $fileText The body of the wiki-page or the document
	 * @param string $path Path to document if external (not in wiki). Might be empty or null
	 * @param unknown $ts Timestamp
	 * @return Apache_Solr_Document
	 */
	public function makeLinkedDocument( $type, $filename, &$fileText, $path, $timestamp ) {
		$doc = $this->oMainControl->makeDocument( 'linked', $type, $filename, $fileText, -1, 998, $path, $timestamp );
		return $doc;
	}

	/**
	 * Indexes all documents that were found in crawl() method.
	 */
	public function indexCrawledDocuments() {
		while ( $document = $this->oDbr->fetchObject( $this->documentsDb ) ) {
			$this->count++;
			if ( !$this->oMainControl->bCommandLineMode ) set_time_limit( $this->iTimeLimit );

			if ( $this->doesLinkedPathFilterMatch( $document->el_to ) ) continue;

			$path = urldecode( $document->el_to );
			$path = str_replace( "file:///", "", $path );

			$ext = preg_split( '#[/\\.]+#', $path );
			$ext = strtolower( array_pop( $ext ) );

			$docType = $this->mimeDecoding( $ext ); // really needed for file extensions? or is it a relict from mw images (repo)
			if ( !isset( $this->aFileTypes[$docType] ) || ( $this->aFileTypes[$docType] !== true ) ) {
				$this->writeLog( ( 'Filetype not allowed: '.$docType.' ('.$path.')' ) );
				continue;
			}

			$size = @filesize( urldecode( $path ) );
			if ( $size === false ) {
				$this->writeLog( ( 'File not accessible: '.$path ) );
				continue;
			}
			if ( $this->sizeExceedsMaxDocSize( $size ) ){
				$this->writeLog( ( 'File exceeds max doc size and will not be indexed: '.$document->el_to ) );
				continue;
			}

			$filename = explode( '/', $path );
			$filename = array_pop( $filename );

			$time = @filemtime( urldecode( $path ) );
			$date = date( "YmdHis", $time );

			// Check if the file is already indexed
			try {
				$uniqueIdForDocument = $this->oMainControl->getUniqueId( -1, $path );
				$hitsDocumentInIndexWithSameUID = $this->oMainControl->oSearchService->search( 'uid:'.$uniqueIdForDocument, 0, 1 );
			} catch ( Exception $e ) {
				$this->writeLog( 'Error indexing file '.$document->el_to.' with errormessage '.$e->getMessage() );
				continue;
			}

			// If already indexed and timestamp is not newer => don't index it!
			if ( $hitsDocumentInIndexWithSameUID->response->numFound != 0 ) {
				// timestamps have different format => compare function to equalize both
				$timestampIndexDoc = $hitsDocumentInIndexWithSameUID->response->docs[0]->ts;
				if ( !$this->isTimestamp1YoungerThanTimestamp2( $date, $timestampIndexDoc ) ) {
					$this->writeLog( ( 'Already in index: '.$document->el_to ) );
					continue;
				}
			}

			$fileInfo = new SplFileInfo( $path );
			$fileRealPath = $fileInfo->getRealPath();
			$fileText =& $this->oMainControl->oSearchService->getFileText( $fileRealPath );
			$doc = $this->makeLinkedDocument( $docType, $filename, $fileText, $path, $date );
			$this->writeLog( $path );
			if ( $doc ) {
				// mode and ERROR_MSG_KEY are only passed for the case when addDocument fails
				$this->oMainControl->addDocument( $doc, $this->mode, self::S_ERROR_MSG_KEY );
			}
		}
	}

	/**
	 * Descructor for BuildIndexMwLinked class
	 */
	public function __destruct() {
		if ( $this->documentsDb !== null )
			$this->oDbr->freeResult( $this->documentsDb );
	}

}