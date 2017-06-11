(function($){
tinymce.PluginManager.add( 'wprainbow' , function( editor ){
	var codeSelect, codeControl, wprainbow = mce_wprainbow;
	
	function setLanguageState( ) {

		var pre = _getPreEl();

		codeSelect.disabled( ! pre );
		codeSelect.value(editor.dom.getAttrib( pre, 'data-language' ));

		if ( !! codeControl ) {
			codeControl.disabled( ! pre );
		}
	}

	function _getPreEl() {
		return editor.dom.getParent( editor.selection.getNode(), 'PRE' );
	}

	function setLanguage( lang ) {
		var el = _getPreEl()
		// wrap in code if
		editor.dom.setAttrib( 
			el,
			'data-language' , 
			lang
		);

		// set no line numbers by default
		if ( ! $( el ).attr( 'data-line' ) ) {
			$( el ).attr( 'data-line', '-1' )
		}
	}
	function setLineNumber( line_number ) {
		editor.dom.setAttrib( 
			_getPreEl(), 
			'data-line' , 
			line_number
		);
	}

	function openControlPanel() {
		var el = _getPreEl(),
			lang_value = $( el ).attr( 'data-language' ),
			line_value = $( el ).attr( 'data-line' ) || '1',
			line_enabled = line_value != "-1",
			windowId = 'wp_rainbow_code_properties';

		function findByName( container, name ) {
			var result;

			if ( ('function' === typeof container.name ) && container.name() == name ) {
				return container;
			}

			if ( 'function' === typeof container.items ) {
				$.each( container.items(), function( i, item ) {
					result = findByName( item, name );
					if ( !! result ) {
						return false;
					}
				} );
			}
			return result;
		}

		function getWin() {
			var currentWin;
			$.each( editor.windowManager.getWindows(), function(i,win) {
				if ( win.settings.id === windowId ) {
					currentWin = win;
					return false;
				}
			});
			return currentWin;
		}
		editor.windowManager.open({
			id: windowId,
			title: wprainbow.l10n.code_properties,
			body: [
					{	
						type: 'listbox', 
						name: 'language', 
						size: 40, 
						label: wprainbow.l10n.code_language, 
						values: wprainbow.languages,
						value: lang_value 
					},
					{
						type:'checkbox',
						name:'enable_line_numbering',
						label: wprainbow.l10n.line_numbers, 
						value: "1",
						checked: line_enabled,
						onclick: function(e){
							// checkbox
							var checked = $( this.getEl() ).is('[aria-checked="true"]'),
								lang_input = findByName( getWin(), 'language' ),
								line_input = findByName( getWin(), 'starting_line' ); // örx!

							if ( checked ) {
								if ( -1 === parseInt( line_input.value() ) ) {
									line_input.value(1);
								}
								if ( '' === lang_input.value() ) {
									lang_input.value( 'generic' );
								}
							}
						}
					},
					{
						type:'textbox',
						name:'starting_line',
						label: wprainbow.l10n.starting_line, 
						value: line_value
					}
				],
			onsubmit: function(e) {
				// set language
				setLanguage( e.data.language );
				
				var line = -1;
				if (e.data.enable_line_numbering) {
					line = Math.abs(e.data.starting_line);
					if ( isNaN( line ) ) {
						line = 1;
					}
				}
				// set or del line number
				setLineNumber( line );
			}
		});
	}

	editor.addButton('wprainbow', {
		type: 'listbox',
		text: wprainbow.l10n.code_language,
		tooltip: wprainbow.l10n.code_language,
		menu_button : true,
		classes : 'widget btn fixed-width', 
		onselect: function(e) {
			setLanguage( this.value() );
		},
		values: wprainbow.languages,
		onPostRender: function() {
			codeSelect = this;
			editor.on( 'nodechange', function( event ) {
				setLanguageState( );
			});
			for ( var i in wprainbow.languages ) {
				var lang = wprainbow.languages[i].value;
				if ( !! lang ) {
					editor.contentStyles.push( 'pre[data-language="'+lang+'"]:before { content:"'+wprainbow.languages[i].text+'" }' );
				}
			}
		}
		
	});

	editor.addButton('wprainbow_codecontrol', {
		tooltip: wprainbow.l10n.code_properties,
		onclick: openControlPanel,
		onPostRender: function() {
			codeControl = this;
			editor.on( 'nodechange', function( event ) {
				setLanguageState( );
			});
		}
	});
	
} );
})(jQuery);