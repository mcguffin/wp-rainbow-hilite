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
	function _getCodeEl() {
		var $pre = $( _getPreEl() ),
			$code, ln;

		// fix markup
		if ( ! $pre.children().first().is('code') ) {
			ln = $pre.attr( 'data-line' );
			$pre.html( '<code>' + $pre.html() + '</code>' );
			$code = $pre.children().first();

			if ( ln ) {
				$pre.removeAttr('data-line');
				$code.attr( 'data-line', ln );
			} else {
				$code.attr( 'data-line', '-1' );
			}
		}
		return $pre.children().get(0);
	}
	function setLanguage( lang ) {
		var pre = _getPreEl(),
			code = _getCodeEl();

		if ( lang === '' && ! $(code).attr('data-line') ) {
			$(pre).html( $(code).html() );
		}

		// wrap in code if
		editor.dom.setAttrib( 
			pre,
			'data-language' , 
			lang
		);

	}
	function setLineNumber( line_number ) {
		editor.dom.setAttrib( 
			_getCodeEl(), 
			'data-line' , 
			line_number
		);
	}

	function openControlPanel() {
		var lang_value = $( _getPreEl() ).attr( 'data-language' ),
			line_value = $( _getCodeEl() ).attr( 'data-line' ) || '1',
			line_enabled = line_value != "-1";

		editor.windowManager.open({
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
						checked: line_enabled
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
					if ( isNaN( line ) )
						line = 1;
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