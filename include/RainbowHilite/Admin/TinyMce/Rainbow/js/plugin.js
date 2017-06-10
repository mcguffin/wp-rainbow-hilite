tinymce.PluginManager.add( 'shy' , function( editor ){
	var the_shy  			= '&shy;',
		the_visible_shy		= '<img title="'+mce_shy.l10n.soft_hyphen+'" data-wp-entity="shy" src="'+tinymce.Env.transparentSrc+'" data-mce-resize="false" data-mce-placeholder="1" />';
//	var the_visible_br = '<img title="Linebreak" data-wp-entity="br" src="'+tinymce.Env.transparentSrc+'" data-mce-resize="false" data-mce-placeholder="1" />';

	
	function _do_shy(o) {
		return o.split( the_shy ).join( the_visible_shy );
	}
	
	editor.addCommand( 'cmd_shy', function() {
 		editor.insertContent(the_shy);
	});
	
	editor.on( 'BeforeSetContent',function(o) {
		o.content = _do_shy(o.content);
	});
	editor.on( 'PostProcess', function(e) {
		if ( e.get ) {
			e.content = e.content.replace(/<img[^>]+>/g, function( image ) {
				if ( image.indexOf( 'data-wp-entity="shy"' ) !== -1 ) {
					return the_shy;
				}
				return image;
			});
		}
	});
	editor.addButton('shy', {
		icon: 'shy',
		tooltip: mce_shy.l10n.insert_soft_hyphen,
		cmd : 'cmd_shy',
		onPostRender: function() {
			var shyBtn = this;
			editor.on( 'nodechange', function( event ) {
				var shy_around = false;
				shyBtn.disabled( ! editor.selection.isCollapsed() && ! shy_around );
			});
		}
	});

	function toggleInvisibles( state ) {
		var $root = jQuery( editor.dom.getRoot() ),
			new_state;
		if ( ! state ) {	
			$root.toggleClass('showinvisible');
		} else if ( state === 'hide' ) {
			$root.removeClass('showinvisible');
		} else if ( state === 'show' ) {
			$root.addClass('showinvisible');
		}
 			
 		new_state = $root.hasClass('showinvisible');
		setUserSetting( 'showinvis', Number( new_state ).toString() );
	}
	
	editor.addCommand( 'cmd_showinvisible', function() {
		toggleInvisibles();
	});

	editor.addButton('showinvisible', {
		icon: 'showinvisible',
		tooltip: mce_shy.l10n.show_invisibles,
		cmd : 'cmd_showinvisible',
		onPostRender: function() {
			var invisBtn = this;
			editor.on( 'nodechange', function( event ) {
				var show_invisibles = jQuery(editor.dom.getRoot()).hasClass('showinvisible');
				invisBtn.active(show_invisibles);
			});
		}
	});
	
	// set initial state
	editor.on('Postrender',function(){
		toggleInvisibles( getUserSetting( 'showinvis' ) === '1' ? 'show' : 'hide' );
	})
	
} );

