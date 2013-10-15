//Wire up buttons in ExtendedEditbar
$(document).ready(function(){

	$('#bs-editbutton-insertfile').click(function( e ){
		e.preventDefault();
		var me = this;
		Ext.require('BS.InsertFile.FileDialog', function(){
			BS.InsertFile.FileDialog.clearListeners();
			BS.InsertFile.FileDialog.on( 'ok', function( dialog, data ) {
				var formattedNamespaces = mw.config.get('wgFormattedNamespaces');
				data.nsText = formattedNamespaces[bs.ns.NS_MEDIA];
				data.caption = data.displayText;
				var wikiLink = new bs.wikiText.Link( data );
				bs.util.selection.restore( wikiLink.toString() );
			});

			var data = {};
			var selection = bs.util.selection.save();
			if( selection !== '' ) {
				var wikiLink = new bs.wikiText.Link( selection );
				if( wikiLink.getNsId() !== bs.ns.NS_MEDIA ) {
					bs.util.alert(
						'bs-insertfile-selection-alert',
						{
							textMsg: 'bs-insertfile-error-no-medialink'
						}
					);
					return;
				}
				data = {
					title: wikiLink.getTitle(),
					displayText: wikiLink.getDisplayText(),
					caption: wikiLink.getCaption() //Same as getDisplayText()
				};
			}

			BS.InsertFile.FileDialog.show( me );
			BS.InsertFile.FileDialog.setData( data );
		});
		
		return false;
	});
	
	$('#bs-editbutton-insertimage').click(function( e ){
		e.preventDefault();
		var me = this;
		Ext.require('BS.InsertFile.ImageDialog', function(){
			BS.InsertFile.ImageDialog.clearListeners();
			BS.InsertFile.ImageDialog.on( 'ok',function( dialog, data ) {
				var formattedNamespaces = mw.config.get('wgFormattedNamespaces');
				data.nsText = formattedNamespaces[bs.ns.NS_IMAGE];
				delete(data.imagename); //Not recognized by wikiText.Link
				var wikiLink = new bs.wikiText.Link( data );
				bs.util.selection.restore( wikiLink.toString() );
			});
			
			var data = {};
			var selection = bs.util.selection.save();
			if( selection !== '' ) {
				var wikiLink = new bs.wikiText.Link( selection );
				if( wikiLink.getNsId() !== bs.ns.NS_IMAGE ) {
					bs.util.alert(
						'bs-insertfile-selection-alert',
						{
							textMsg: 'bs-insertfile-error-no-imagelink'
						}
					);
					return;
				}
				data = wikiLink.getRawProperties();
			}

			BS.InsertFile.ImageDialog.show( me );
			BS.InsertFile.ImageDialog.setData( data );
		});
		return false;
	});
});

// Register buttons with hwactions plugin of VisualEditor
$(document).bind('BsVisualEditorActionsInit', function( event, plugin, buttons, commands ){
	var t = plugin;
	var ed = t.editor;

	//Insert mage
	buttons.push({
		buttonId: 'bsimage',
		buttonConfig: {
			title : mw.message('bs-insertfile-button_image_title').plain(),
			cmd : 'mceBsImage',
			icon: 'image'
		}
	});

	//Insert file
	buttons.push({
		buttonId: 'bsfile',
		buttonConfig: {
			title : mw.message('bs-insertfile-button_file_title').plain(),
			cmd : 'mceBsFile'
		}
	});

	commands.push({
		commandId: 'mceBsImage',
		commandCallback: function() {
			var image = this.selection.getNode();
			var params = {};
			
			if( image.nodeName.toLowerCase() == 'img' ) {
				var data = bs.util.makeAttributeObject( image );
				params = bs.util.unprefixDataAttributeObject(data);
			}

			Ext.require('BS.InsertFile.ImageDialog', function(){
				BS.InsertFile.ImageDialog.clearListeners();
				BS.InsertFile.ImageDialog.on( 'ok', function( sender, data ) {
					var imgAttrs = this.plugins.bswikicode.makeDefaultImageAttributesObject();
					var classAddition = '';
					var styleAddition = '';
					if( data.sizeheight !== '' ) {
						styleAddition += ' height: '+data.sizeheight+'px;'
					}
					if( data.sizewidth !== '' ) {
						styleAddition += ' width: '+data.sizewidth+'px;'
					}
					//TODO: This is ugly stuff from "bswikicode". Find better 
					//solution in the year 2017.
					if( data.thumb == true || data.frame == true ) {
						styleAddition += ' border: 1px solid #CCC; float: right; clear:right; margin-left: 1.4em';
					}
					if( data.align == 'center' || data.center == true ) {
						classAddition += 'center';
						styleAddition += ' display: block; margin-left: auto; margin-right: auto;';
					}
					if( data.align == 'right' || data.right == true ) {
						classAddition += 'tright';
						styleAddition += ' float: right; clear: right; margin-left: 1.4em;';
					}
					if( data.align == 'left' || data.left == true ) {
						classAddition += 'tleft';
						styleAddition += ' float: left; clear: left; margin-right: 1.4em;';
					}
					imgAttrs.src = data.src;
					imgAttrs['class'] += classAddition;
					imgAttrs.style += styleAddition;

					var dataAttrs = bs.util.makeDataAttributeObject( data );
					$.extend(imgAttrs, dataAttrs);
					var newImgNode = this.dom.create( 'img', imgAttrs );
					if( image.nodeName.toLowerCase() == 'img' ) {
						this.dom.replace(newImgNode, image);
					}
					else {
						this.dom.insertAfter(newImgNode, image);
					}
					//Place cursor to end
					this.selection.select(newImgNode, false);
					this.selection.collapse(false);
				}, this);
				
				BS.InsertFile.ImageDialog.show();
				BS.InsertFile.ImageDialog.setData( params );
			}, this);
		}
	});
	
	commands.push({
		commandId: 'mceBsFile',
		commandCallback: function() {
			var anchor = this.selection.getNode();
			var params = {};

			if( anchor.nodeName.toLowerCase() == 'a' ) {
				var prefixedTitle = decodeURIComponent( anchor.getAttribute( 'href' ) );
				var wikiLink = new bs.wikiText.Link( '[['+prefixedTitle+']]');
				params = {
					title: wikiLink.getTitle(),
					displayText: anchor.getAttribute( 'title' ),
					caption:     anchor.getAttribute( 'title' )
				};
			}

			Ext.require('BS.InsertFile.FileDialog', function(){
				BS.InsertFile.FileDialog.clearListeners();
				BS.InsertFile.FileDialog.on( 'ok', function(sender, data) {
					var formattedNamespaces = mw.config.get('wgFormattedNamespaces');
					var nsText = formattedNamespaces[bs.ns.NS_MEDIA];
					var prefixedTitle = nsText + ':' + data.title;
					var newAnchor = this.dom.create(
						'a',
						{
							'title': data.displayText,
							'href': prefixedTitle,
							'class': 'internal bs-internal-link',
							'data-bs-type' : 'internal_link'
						},
						data.displayText
					);
					if( anchor.nodeName.toLowerCase() == 'a' ) {
						this.dom.replace(newAnchor, anchor);
					}
					else {
						this.dom.insertAfter(newAnchor, anchor);
					}
				}, this);
				
				BS.InsertFile.FileDialog.show();
				BS.InsertFile.FileDialog.setData( params );
			}, this);
		}
	});
	
	//Override default command "mceImage"
	commands.push({
		commandId: 'mceImage',
		commandCallback: function( ui, v ) {
			this.execCommand( 'mceHwImage', ui );
		}
	});
	
	//Override default command "mceAdvImage"
	commands.push({
		commandId: 'mceAdvImage',
		commandCallback: function( ui, v ) {
			this.execCommand( 'mceHwImage', ui );
		}
	});
	
	return;
	
	//This is old code. Not sure if needed for TinyMCE 4
	ed.onNodeChange.add(function(ed, cm, element, c, o) {
		cm.setActive(  'bsimage', element.nodeName == 'IMG');
		cm.setDisabled('bsimage', element.nodeName == 'A');
		if (element.nodeName == 'A') {
			if ( t.elementIsCategoryAnchor( element ) ) {
				cm.setActive(  'bsfile', false);
				cm.setDisabled('bsfile', true);
			}
			else if ( t.elementIsMediaAnchor( element ) ) {
				cm.setActive(  'bsfile', true);
				cm.setActive(  'bsfile', false); //Why twice?
				cm.setDisabled('bsfile', false);
			}
			else {
				cm.setDisabled('bsfile', true);
			}
		}
	});
});