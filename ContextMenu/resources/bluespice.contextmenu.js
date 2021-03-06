(function(mw, $, bs){
	var showMenu = function( anchor, items, e ) {
		$(document).trigger( 'BSContextMenuBeforeCreate', [anchor, items]);

		var menu = new Ext.menu.Menu({
			id: 'bs-cm-menu',
			items: items,
			listeners: {
				/*
				 * Unfortunately ExtJS does not use "close" when the context
				 * menu disappears, but hide. Therefore closeAction: 'destroy',
				 * wich is default does not work. But as we use DOM IDs we
				 * really need to remove the tem from the DOM, otherwise we
				 * get ugly collisions when a secon menu is opened.
				 */
				hide:function(menu, opt){
					Ext.destroy(menu);
				}
			}
		});
		menu.showAt(e.pageX, e.pageY);

		e.preventDefault();
		return false;
	};

	var appendSection = function(base, additions, sectionkey) {
		if( base.length > 0 ) {
			base.push('-');
		}
		var additionsLength = additions.length;
		for( var i = 0; i < additionsLength; i++ ) {
			base.push( additions[i] );
		}

		return base;
	};

	var modus = mw.user.options.get('MW::ContextMenu::Modus', 'ctrl');

	$(document).on( 'contextmenu', 'a', function( e ) {
		if( (modus === 'no-ctrl' && e.ctrlKey) || (modus === 'ctrl' && !e.ctrlKey) ) {
			return true;
		}

		var anchor = $(this);

		if( anchor.hasClass('external') ) {
			return true;
		}

		mw.loader.using( 'ext.bluespice.extjs', function() {
			var items = [];

			bs.api.tasks.exec(
				'contextmenu',
				'getMenuItems',
				{
					title: anchor.data('bs-title')
				}
			).done( function( response )  {
				if( response.payload_count > 0 ) {
					for(item in response.payload.items){
						items.push(response.payload.items[item]);
					}
					showMenu( anchor, items, e );
				}
			});
		});

		return false;
	});

})( mediaWiki, jQuery, blueSpice);