tinymce.PluginManager.add( 'wprainbow' , function( editor ){

	function setState( button, node ) {
		var parent_node = editor.dom.getParent( editor.selection.getNode() ,'PRE');
		button.disabled( ! parent_node );
		button.value(editor.dom.getAttrib( parent_node, 'data-language' ));
	}

	
	editor.addButton('wprainbow', {
		type: 'listbox',
		text: wprainbow.l10n.code_style,
		menu_button : true,
		classes : 'widget btn fixed-width', 
		onselect: function(e) {
			editor.dom.setAttrib( 
				editor.dom.getParent( editor.selection.getNode() ,'PRE') , 
				'data-language' , 
				this.value() 
			);
		},
		values: wprainbow.languages,
		onPostRender: function() {
			codeSelect = this;
			editor.on( 'nodechange', function( event ) {
				setState( codeSelect, event.element );
			});
			
			for ( var i in wprainbow.languages ) {
				var lang = wprainbow.languages[i].value;
				if ( !! lang )
					editor.contentStyles.push( 'pre[data-language="'+lang+'"]:before { content:"'+wprainbow.languages[i].text+'" }' );
			}
		}
		
	});
	(function($){
		

	})(jQuery);

} );



