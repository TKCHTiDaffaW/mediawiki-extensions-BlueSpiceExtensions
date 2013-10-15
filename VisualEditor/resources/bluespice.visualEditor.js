/**
 * VisualEditor extension
 *
 * @author     Markus Glaser <glaser@hallowelt.biz>
 * @version    1.20.0
 
 * @package    Bluespice_Extensions
 * @subpackage VisualEditor
 * @copyright  Copyright (C) 2011 Hallo Welt! - Medienwerkstatt GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */

// TODO MRG (09.10.13 10:28): This is deprecated

$(document).on('VisualEditor::instanceShow', function(event, editorId) {
	if (editorId === 'wpTextbox1') {
		$('#toolbar').hide();
		$('#hw-toolbar').hide(); //Deprecated
		$('#bs-extendededitbar').hide();
	}
});
$(document).on('VisualEditor::instanceHide', function(event, editorId) {
	if (editorId === 'wpTextbox1') {
		$('#toolbar').show();
		$('#hw-toolbar').show(); //Deprecated
		$('#bs-extendededitbar').show();
	}
});
mw.loader.using( 'ext.bluespice.visualEditor', function() {
	var currentSiteCSS = [];
	//We collect the CSS Links from this document and set them as content_css 
	//for TinyMCE
	$('link[rel=stylesheet]').each(function(){
		var cssBaseURL = '';
		var cssUrl = $(this).attr('href');
		//Conditionally make urls absolute to avoid conflict with tinymce.baseURL
		if( cssUrl.startsWith( '/' ) ) cssBaseURL = mw.config.get('wgServer');
		currentSiteCSS.push( cssBaseURL + cssUrl );
	});
	
	VisualEditor.getInstance().setConfig('editpage', {
		height: 550,
		content_css: currentSiteCSS.join(',')
	});

	if (mw.config.get('BsVisualEditorUseTidy') === true) {
		VisualEditor.startEditors();
		$(document).trigger('VisualEditor::instanceShow', ['wpTextbox1']);
	}
});

$(document).on('click', 'a#bs-editbutton-visualeditor', function(e) {
	e.preventDefault();
	//todo: check ob richtig, denke durch 'wpTextbox1' wird in tinymce.startup.js ln 95
	//eine Instanz des tiny erzeugt, der mit seiner id den MW-Editor überschreibt => kein speichern möglich
	//VisualEditor.toggleEditor('wpTextbox1');
	
	$(document).trigger('VisualEditor::instanceShow', ['wpTextbox1']);
	VisualEditor.startEditors();
	return false;
});