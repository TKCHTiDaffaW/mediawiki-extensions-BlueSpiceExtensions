<?php
/**
 * Internationalisation file for ExtendedSearch
 *
 * Part of BlueSpice for MediaWiki
 *
 * @author     Stephan Muggli <muggli@hallowelt.biz>
 * @package    BlueSpice_Extensions
 * @subpackage ExtendedSearch
 * @copyright  Copyright (C) 2012 Hallo Welt! - Medienwerkstatt GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */

$messages = array();

$messages['en'] = array(
	'bs-extendedsearch-extension-description' => 'Extended search',
	'bs-extendedsearch-articles' => 'Article',
	'bs-extendedsearch-file' => 'File',
	'bs-extendedsearch-category-filter' => 'Categories',
	'bs-extendedsearch-curl-not-active' => 'The curl-Extension is not activated. This might cause some errors.',
	'bs-extendedsearch-error' => 'Error',
	'bs-extendedsearch-error-indexing-wiki' => 'An error occurred indexing of articles in wiki.',
	'bs-extendedsearch-error-indexing-repo'  => 'An error occurred indexing of uploaded files (wiki repository).',
	'bs-extendedsearch-error-indexing-linked' => 'An error occurred indexing linked files that are referenced in wiki articles (file://)',
	'bs-extendedsearch-error-indexing-special-linked' => 'An error occurred indexing of linked urls in wiki articles (speciallinked)',
	'bs-extendedsearch-extended-options' => 'Options',
	'bs-extendedsearch-facet-category' => 'Category',
	'bs-extendedsearch-facet-delete' => 'Delete filter',
	'bs-extendedsearch-facet-editors' => 'Authors',
	'bs-extendedsearch-facet-namespace' => 'Namespace',
	'bs-extendedsearch-facet-namespace-files' => 'Files',
	'bs-extendedsearch-facet-namespace-main' => 'Article',
	'bs-extendedsearch-facet-noeditors' => 'Not known',
	'bs-extendedsearch-facet-type' => 'Format',
	'bs-extendedsearch-facet-uncategorized' => 'Uncategorized',
	'bs-extendedsearch-files' => 'Search files',
	'bs-extendedsearch-filter' => 'Filter',
	'bs-extendedsearch-file_protocol_not_activated' => 'For indexing linked files in wiki articles to make sense the array $wgUrlProtocols has to be enhanced with value \'file:\'.',
	'bs-extendedsearch-finished' => 'Finished',
	'bs-extendedsearch-fulltextsearch' => 'Fulltext',
	'bs-extendedsearch-fuzzy' => 'An exact search for <b>$1</b> found no results. There {{PLURAL:$2|is $2 similar result|are $2 similar results}}.',
	'bs-extendedsearch-indexing_files_in_repo' => 'Indexing uploaded files',
	'bs-extendedsearch-indexing_external_files_in_repo' => 'Indexing external files',
	'bs-extendedsearch-indexing_linked_files' => 'Indexing linked files',
	'bs-extendedsearch-indexing_wiki_articles' => 'Indexing wiki articles',
	'bs-extendedsearch-indexing_specialpages' => 'Indexing specialpages',
	'bs-extendedsearch-invalid-query' => 'The query could not be processed',
	'bs-extendedsearch-mlt-results' => '$2 hits similar to article <b>$1</b> were found.',
	'bs-extendedsearch-no_allow_url_include' => 'Indexing not possible: allow_url_fopen',
	'bs-extendedsearch-no_result' => 'Search for <b>$1</b> found no matches',
	'bs-extendedsearch-no_search_term' => 'Please enter a search term',
	'bs-extendedsearch-result' => 'Search for <b>$1</b> found {{PLURAL:$2|$2 result|$2 results}}',
	'bs-extendedsearch-remove-filter' => 'remove',
	'bs-extendedsearch-result-caption' => 'results',
	'bs-extendedsearch-search' => 'Search',
	'bs-extendedsearch-search-category' => 'Search in the categories:',
	'bs-extendedsearch-search-editors' => 'Search for authors:',
	'bs-extendedsearch-search-help-multiselect' => 'Hold CTRL to select multiple items',
	'bs-extendedsearch-search-namespace' => 'Search in the namespaces:',
	'bs-extendedsearch-search-wiki' => 'Search in the wiki',
	'bs-extendedsearch-server-not-available' => 'The search server is not available',
	'bs-extendedsearch-sort-by' => 'sorted by:',
	'bs-extendedsearch-sort-relevance' => 'Relevance',
	'bs-extendedsearch-sort-title' => 'Title',
	'bs-extendedsearch-sort-ts' => 'Time',
	'bs-extendedsearch-sort-type' => 'Type',
	'bs-extendedsearch-specialpage-form-return-to-simple' => 'Basic searchform',
	'bs-extendedsearch-specialpage-form-expand-to-extended' => 'Extended searchform',
	'bs-extendedsearch-titlesearch' => 'Title',
	'bs-extendedsearch-searchfulltext' => 'Containing ...',
	'specialextendedsearch' => 'Extended Search',
	'prefs-ExtendedSearch' => 'Extended Search',
	'prefs-Search' => 'Extended Search',
	'bs-extendedsearch-pref-searchfiles' => 'Search files by default',
	'bs-extendedsearch-pref-defscopeuser' => 'Default for search',
	'bs-extendedsearch-pref-scope-text' => 'Fulltext',
	'bs-extendedsearch-pref-scope-title' => 'Title',
	'bs-extendedsearch-pref-jumptotitle' => 'Redirect to article if exists',
	'bs-extendedsearch-pref-indextylinked' => 'Index files that are linked in wiki articles',
	'bs-extendedsearch-pref-indextypesrepo' => 'Index uploaded files',
	'bs-extendedsearch-pref-indextypesspeciallinked' => 'Index files that are linked in wiki articles but protected',
	'bs-extendedsearch-pref-indextypeswiki' => 'Index wiki articles',
	'bs-extendedsearch-pref-indextypesspecial' => 'Index specialpages',
	'bs-extendedsearch-pref-limitresultsuser' => 'Number of results on specialpage',
	'bs-extendedsearch-pref-limitresultdef' => 'Number of results on specialpage',
	'bs-extendedsearch-pref-showcategoryfield' => 'Show box category filter (in search form)',
	'bs-extendedsearch-pref-showcreatesugg' => 'Show create/suggest links on specialpage',
	'bs-extendedsearch-pref-showfacets' => 'Show facets on specialpage',
	'bs-extendedsearch-pref-shownamespacefield' => 'Show box namespace filter (in search form)',
	'bs-extendedsearch-pref-showautocomplete' => 'Show autocomplete menu',
	'bs-extendedsearch-pref-externalrepo' => 'Index external Directory',
	'bs-extendedsearch-pref-autocfulltext' => 'Show fulltext option in autocomplete',
	'bs-extendedsearch-pref-acentries' => 'Number of results in autocomplete',
	'bs-extendedsearch-pref-customerid' => 'Unique ID (necessary to run more than one bluespice with one solr server)',
	'bs-extendedsearch-pref-formmethod' => 'Search form to be sent with \'post\' or \'get\'',
	'bs-extendedsearch-pref-highlightsnippets' => 'Number of highlight snippets',
	'bs-extendedsearch-pref-indexfiletypes' => 'File extensions to be indexed',
	'bs-extendedsearch-pref-indextypesexternal' => 'Index external directories',
	'bs-extendedsearch-pref-logging' => 'Statistical evaluation of queries',
	'bs-extendedsearch-pref-logusers' => 'Log username with queries',
	'bs-extendedsearch-pref-maxdocsizemb' => 'Maximum size of files during indexing',
	'bs-extendedsearch-pref-solrpingtime' => 'Ping time (in seconds)',
	'bs-extendedsearch-pref-showcresuginac' => 'Show create/suggest links in autocomplete',
	'bs-extendedsearch-pref-showspell' => 'Show Spellchecker on specialpage',
	'bs-extendedsearch-pref-solrserviceurl' => 'Solr URL',
	'bs-extendedsearch-pref-setfocus' => 'Automatically focus searchbox (articles only)',
	'bs-extendedsearch-pref-numfacets' => 'Number of facets',
	'bs-extendedsearch-pref-showmlt' => 'Show similar articles',
	'bs-extendedsearch-pref-solrcore' => 'Solr core instance',
	'bs-extendedsearch-about_to_start' => 'Starting...',
	'bs-extendedsearch-label' => 'Search',
	'bs-extendedsearch-index-successfully-deleted' => 'The index was successfully deleted.',
	'bs-extendedsearch-create-index' => 'Re-create index',
	'bs-extendedsearch-delete-index' => 'Delete index',
	'bs-extendedsearch-overwrite-index' => 'Overwrite index',
	'bs-extendedsearch-docs-in-index' => 'documents indexed.',
	'bs-extendedsearch-error' => 'Error',
	'bs-extendedsearch-finished' => 'Finished',
	'bs-extendedsearch-index_doesnt_exist' => 'Index does not exist',
	'bs-extendedsearch-indexing_linked_files' => 'Indexing linked files',
	'bs-extendedsearch-index_optimized' => 'The Index has been optimized.',
	'bs-extendedsearchadmin-label' => 'Search options',
	'bs-extendedsearch-optimize_index' => 'Optimize index',
	'bs-extendedsearch-stats' => 'Statistics',
	'bs-extendedsearch-status' => 'Status',
	'bs-extendedsearch-index-error-deleting' => 'Error deleting index, search service returned http-status $1',
	'bs-extendedsearch-no-success-deleting' => 'No success on deleting the index.<br />This exception from search service has been caught:',
	'bs-extendedsearch-no-success-optimizing' => 'No success on optimizing the index.<br />This exception from search service has been caught:',
	'bs-extendedsearch-stats-not-implemented' => 'Statistics not yet implemented.',
	'bs-extendedsearch-statistics' => 'Statistics',
	'bs-extendedsearch-ac-heading' => 'Results',
	'bs-extendedsearch-facet-namespace-extfiles' => 'External files',
	'bs-extendedsearch-indexinginprogress' => 'The index is already being created at the moment.',
	'bs-extendedsearch-delete-lock' => 'Delete lock file',
	'bs-extendedsearch-warning' => 'Warning',
	'bs-extendedsearch-lockfiletext' => 'Please, only delete the lock file if you are sure the indexing is not in progress!',
	'bs-extendedsearch-did-you-mean' => 'Did you mean $1?',
	'bs-extendedsearch-indexingcomponent' => 'Indexing component',
	'bs-extendedsearch-spell' => 'Spellchecker',
	'bs-extendedsearch-outof' => 'out of',
	'bs-extendedsearch-asc' => 'Ascending',
	'bs-extendedsearch-desc' => 'Descending',
	'bs-extendedsearch-unknown' => 'Unknown',
	'bs-extendedsearch-section' => 'Section ',
	'bs-extendedsearch-morelikethis' => 'Similar articles',
	'bs-extendedsearch-recentsearchterms' => 'Recent search terms',
	'bs-extendedsearch-recentsearchtermsdesc' => 'List of the most recent searched terms inside this Wiki sorted by count',
	'bs-extendedsearch-no-mlt-found' => 'No similar articles found',
	'bs-extendedsearch-pref-mltns' => '"More like this" namespaces',
	'bs-extendedsearch-redirect' => 'Redirect from',
	'bs-extendedsearch-totalnoofarticles' => 'Number of articles to index',

	//Javascript
	'bs-extendedsearch-more' => 'More',
	'bs-extendedsearch-fewer' => 'Fewer'
);

$messages['de'] = array(
	'bs-extendedsearch-extension-description' => 'Erweiterte Suche',
	'bs-extendedsearch-articles' => 'Artikel',
	'bs-extendedsearch-file' => 'Datei',
	'bs-extendedsearch-category-filter' => 'Kategorien',
	'bs-extendedsearch-curl-not-active' => 'Die curl-Extension ist nicht aktiviert. Dies kann zu Problemen führen.',
	'bs-extendedsearch-error' => 'Fehler',
	'bs-extendedsearch-error-indexing-wiki' => 'Fehler beim Indexieren der Wiki-Artikel.',
	'bs-extendedsearch-error-indexing-repo' => 'Fehler beim Indexieren der hochgeladenen Dateien (Wiki Repository)',
	'bs-extendedsearch-error-indexing-linked' => 'Fehler beim Indexieren der in Artikeln verlinkten Dateien (file://)',
	'bs-extendedsearch-error-indexing-special-linked' => 'Fehler beim Indexieren der in Artikeln verlinkten URLs (speciallinked)',
	'bs-extendedsearch-extended-options' => 'Optionen',
	'extension-description' => 'Erweiterte Suche.',
	'bs-extendedsearch-facet-category' => 'Kategorie',
	'bs-extendedsearch-facet-delete' => 'Filter löschen',
	'bs-extendedsearch-facet-editors' => 'Autoren',
	'bs-extendedsearch-facet-namespace' => 'Namensraum',
	'bs-extendedsearch-facet-namespace-files' => 'Dateien',
	'bs-extendedsearch-facet-namespace-main' => 'Artikel',
	'bs-extendedsearch-facet-noeditors' => 'Keine Angabe',
	'bs-extendedsearch-facet-type' => 'Format',
	'bs-extendedsearch-facet-uncategorized' => 'Unkategorisiert',
	'bs-extendedsearch-file_protocol_not_activated' => 'Damit das Indexieren verlinkter Dateien sinnvoll ist muss dem array $wgUrlProtocols der Wert \'file:\' hinzugefügt werden.',
	'bs-extendedsearch-files' => 'Dateien durchsuchen',
	'bs-extendedsearch-filter' => 'Filter',
	'bs-extendedsearch-fulltextsearch' => 'Volltext',
	'bs-extendedsearch-fuzzy' => 'Die exakte Suche nach <b>$1</b> fand keine Ergebnisse. Es {{PLURAL:$2|wurde $2 ähnliches Ergebnis|wurden $2 ähnliche Ergebnisse}} gefunden.',
	'bs-extendedsearch-indexing_files_in_repo' => 'Indexiere hochgeladene Dateien',
	'bs-extendedsearch-indexing_external_files_in_repo' => 'Indexiere externe Dateien',
	'bs-extendedsearch-indexing_linked_files' => 'Indexiere verlinkte Dateien',
	'bs-extendedsearch-indexing_wiki_articles' => 'Indexiere Wiki-Artikel',
	'bs-extendedsearch-indexing_specialpages' => 'Indexiere Spezialseiten',
	'bs-extendedsearch-invalid-query' => 'Die Abfrage konnte nicht verarbeitet werden',
	'bs-extendedsearch-mlt-results' => 'Zum Artikel <b>$1</b> wurden $2 ähnliche Treffer gefunden.',
	'bs-extendedsearch-no_allow_url_include' => 'Indexierung nicht möglich: allow_url_fopen',
	'bs-extendedsearch-no_result' => 'Die Suche nach <b>$1</b> findet keine Treffer',
	'bs-extendedsearch-no_search_term' => 'Bitte gib einen Suchbegriff ein',
	'bs-extendedsearch-result' => '{{PLURAL:$2|$2 Ergebnis|$2 Ergebnisse}} für <b>$1</b> gefunden',
	'bs-extendedsearch-remove-filter' => 'aufheben',
	'bs-extendedsearch-result-caption' => 'Ergebnissen',
	'bs-extendedsearch-search' => 'Suchen',
	'bs-extendedsearch-search-category' => 'Suche in Kategorien:',
	'bs-extendedsearch-search-editors' => 'Suche nach Autoren:',
	'bs-extendedsearch-search-help-multiselect' => 'Für Mehrfachauswahl CTRL gedrückt halten',
	'bs-extendedsearch-search-namespace' => 'Suche in Namensräumen:',
	'bs-extendedsearch-search-wiki' => 'Wiki durchsuchen',
	'bs-extendedsearch-server-not-available' => 'Der Suchserver ist nicht erreichbar',
	'bs-extendedsearch-sort-by' => 'sortiert nach:',
	'bs-extendedsearch-sort-relevance' => 'Relevanz',
	'bs-extendedsearch-sort-title' => 'Titel',
	'bs-extendedsearch-sort-ts' => 'Zeit',
	'bs-extendedsearch-sort-type' => 'Typ',
	'bs-extendedsearch-specialpage-form-return-to-simple' => 'Einfaches Suchformular',
	'bs-extendedsearch-specialpage-form-expand-to-extended' => 'Erweitertes Suchformular',
	'bs-extendedsearch-titlesearch' => 'Titel',
	'bs-extendedsearch-searchfulltext' => 'Volltextsuche nach ...',
	'specialextendedsearch' => 'Erweiterte Suche',
	'prefs-ExtendedSearch' => 'Erweiterte Suche',
	'prefs-Search' => 'Erweiterte Suche',
	'bs-extendedsearch-pref-searchfiles' => 'Dateien durchsuchen standardmäßig aktivieren',
	'bs-extendedsearch-pref-defscopeuser' => 'Standard für Suche',
	'bs-extendedsearch-pref-scope-text' => 'Volltext',
	'bs-extendedsearch-pref-scope-title' => 'Titel',
	'bs-extendedsearch-pref-jumptotitle' => 'Weiterleitung zum Artikel, wenn er vorhanden',
	'bs-extendedsearch-pref-indextylinked' => 'Im Wiki-Artikel verlinkte Dateien indexieren',
	'bs-extendedsearch-pref-indextypesrepo' => 'Hochgeladene Dateien indexieren',
	'bs-extendedsearch-pref-indextypesspeciallinked' => 'Im Wiki-Artikel Spezial-Verlinkungen indexieren',
	'bs-extendedsearch-pref-indextypeswiki' => 'Wiki Inhalte indexieren',
	'bs-extendedsearch-pref-indextypesspecial' => 'Spezialseiten indexieren',
	'bs-extendedsearch-pref-limitresultsuser' => 'Begrenzung der Such-Treffer in der Ergebnisliste',
	'bs-extendedsearch-pref-limitresultdef' => 'Begrenzung der Such-Treffer in der Ergebnisliste',
	'bs-extendedsearch-pref-showcategoryfield' => 'Box Kategorie-Filter (im Suchformular)',
	'bs-extendedsearch-pref-showcreatesugg' => 'Erstellen/Vorschlagen Links auf der Spezialseite anzeigen',
	'bs-extendedsearch-pref-showfacets' => 'Facettensuche aktivieren',
	'bs-extendedsearch-pref-shownamespaceField' => 'Box Namespace-Filter (im Suchformular)',
	'bs-extendedsearch-pref-showautocomplete' => 'Autocomplete Menü anzeigen',
	'bs-extendedsearch-pref-externalrepo' => 'Externes Verzeichnis indexieren',
	'bs-extendedsearch-pref-autocfulltext' => 'Volltext Suchoption im Autocomplete Dialog anzeigen',
	'bs-extendedsearch-pref-acentries' => 'Anzahl der Autocomplete-Ergebnisse',
	'bs-extendedsearch-pref-customerid' => 'Eindeutige Kennung (um mehrere bluespice mit einem Suchserver zu betreiben)',
	'bs-extendedsearch-pref-formmethod' => 'Absenden Suchformular mittels \'post\' oder \'get\'',
	'bs-extendedsearch-pref-highlightsnippets' => 'Anzahl Zeilen für die Markierung gefundener Textpassagen (Ergebnisliste)',
	'bs-extendedsearch-pref-indexfiletypes' => 'Dateiendungen zum Indexieren',
	'bs-extendedsearch-pref-indextypesexternal' => 'Externe Verzeichnisse durchsuchbar machen',
	'bs-extendedsearch-pref-logging' => 'Statistische Auswertung der Suchanfragen',
	'bs-extendedsearch-pref-logusers' => 'Speicherung des Nutzernamens zu den Suchanfragen',
	'bs-extendedsearch-pref-maxdocsizemb' => 'Maximale Dateigröße beim Indexieren',
	'bs-extendedsearch-pref-solrpingtime' => 'Ping-Zeit (in Sekunden)',
	'bs-extendedsearch-pref-showcresuginac' => 'Erstellen/Vorschlagen Links in dem Autocomplete Menü anzeigen',
	'bs-extendedsearch-pref-showspell' => 'Spellchecker auf der Spezialseite anzeigen',
	'bs-extendedsearch-pref-solrserviceurl' => 'Solr URL',
	'bs-extendedsearch-pref-setfocus' => 'Automatisch Suchfeld fokusieren (nur Artikel)',
	'bs-extendedsearch-pref-numfacets' => 'Anzahl der Facetten',
	'bs-extendedsearch-pref-showmlt' => 'Ähnliche Artikel anzeigen',
	'bs-extendedsearch-pref-solrcore' => 'Solr Core Instanz',
	'bs-extendedsearch-about_to_start' => 'Starte...',
	'bs-extendedsearch-label' => 'Suche konfigurieren',
	'bs-extendedsearch-index-successfully-deleted' => 'Der Index wurde erfolgreich gelöscht.',
	'bs-extendedsearch-create-index' => 'Index neu erstellen',
	'bs-extendedsearch-delete-index' => 'Index löschen',
	'bs-extendedsearch-overwrite-index' => 'Index &uuml;berschreiben',
	'bs-extendedsearch-docs-in-index' => 'Dokumente indexiert.',
	'bs-extendedsearch-error' => 'Fehler',
	'bs-extendedsearch-finished' => 'Fertig',
	'bs-extendedsearch-index_doesnt_exist' => 'Der Index wurde noch nicht erstellt',
	'bs-extendedsearch-indexing_linked_files' => 'Indexiere verlinkte Dateien',
	'bs-extendedsearch-index_optimized' => 'Der Index wurde optimiert.',
	'bs-extendedsearchadmin-label' => 'Sucheinstellungen',
	'bs-extendedsearch-stats' => 'Statistik',
	'bs-extendedsearch-status' => 'Status',
	'bs-extendedsearch-index-error-deleting' => 'Fehler beim Löschen des Index. Der Suchservice antwortete mit HTTP Status $1',
	'bs-extendedsearch-no-success-deleting' => 'Löschen des Indexes war erfolgos.<br />Diese Fehlermeldung des Suchservices wurde festgehalten:',
	'bs-extendedsearch-no-success-optimizing' => 'Optimieren des Indexes war erfolgos.<br />Diese Fehlermeldung des Suchservices wurde festgehalten:',
	'bs-extendedsearch-stats-not-implemented' => 'Statistik ist noch nicht implementiert.',
	'bs-extendedsearch-statistics' => 'Statistik',
	'bs-extendedsearch-ac-heading' => 'Ergebnisse',
	'bs-extendedsearch-facet-namespace-extfiles' => 'Externe Dateien',
	'bs-extendedsearch-indexinginprogress' => 'Der Index wird momentan neu erstellt.',
	'bs-extendedsearch-delete-lock' => 'Lockdatei löschen',
	'bs-extendedsearch-warning' => 'Warnung',
	'bs-extendedsearch-lockfiletext' => 'Lösche die Lockdatei nur, wenn du dir sicher bist, dass kein Indexierprozess läuft!',
	'bs-extendedsearch-did-you-mean' => 'Meintest du $1?',
	'bs-extendedsearch-indexingcomponent' => 'Indexiere Komponente',
	'bs-extendedsearch-spell' => 'Spellchecker',
	'bs-extendedsearch-outof' => 'von',
	'bs-extendedsearch-asc' => 'Aufsteigend',
	'bs-extendedsearch-desc' => 'Absteigend',
	'bs-extendedsearch-unknown' => 'Unbekannt',
	'bs-extendedsearch-section' => 'Abschnitt ',
	'bs-extendedsearch-morelikethis' => 'Ähnliche Artikel',
	'bs-extendedsearch-recentsearchterms' => 'Meist gesuchte Begriffe',
	'bs-extendedsearch-recentsearchtermsdesc' => 'Liste der am häufigest gesuchten Begriffe dieses Wikis, sortiert nach der Anzahl',
	'bs-extendedsearch-no-mlt-found' => 'Keine ähnlichen Artikel gefunden',
	'bs-extendedsearch-pref-mltns' => '"More like this" Namensräume',
	'bs-extendedsearch-redirect' => 'Weiterleitung von',
	'bs-extendedsearch-totalnoofarticles' => 'Zu indexierende Artikel',

	//Javascript
	'bs-extendedsearch-more' => 'Mehr',
	'bs-extendedsearch-fewer' => 'Weniger'
);

$messages['de-formal'] = array(
	'bs-extendedsearch-no_search_term' => 'Bitte geben Sie einen Suchbegriff ein',
	'bs-extendedsearch-lockfiletext' => 'Löschen Sie die Lockdatei nur, wenn Sie sich sicher sind, dass kein Indexierprozess läuft!',
	'bs-extendedsearch-did-you-mean' => 'Meinten Sie $1?'
);

$messages['qqq'] = array();
