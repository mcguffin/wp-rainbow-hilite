tinymce.PluginManager.add( 'wprainbow' , function( editor ){
	var codeSelect, codeControl;
	
	function setLanguageState( ) {
		var parent_node = editor.dom.getParent( editor.selection.getNode() ,'PRE');
		codeSelect.disabled( ! parent_node );
		codeSelect.value(editor.dom.getAttrib( parent_node, 'data-language' ));
		if ( !! codeControl )
			codeControl.disabled( ! parent_node );
	}
	function setLanguage( lang ) {
		editor.dom.setAttrib( 
			editor.dom.getParent( editor.selection.getNode() ,'PRE') , 
			'data-language' , 
			lang
		);
	}
	function setLineNumber( line_number ) {
		editor.dom.setAttrib( 
			editor.dom.getParent( editor.selection.getNode() ,'PRE') , 
			'data-line' , 
			line_number
		);
	}
	function openControlPanel() {
		var parent_node = editor.dom.getParent( editor.selection.getNode() ,'PRE');
		var lang_value = editor.dom.getAttrib( parent_node, 'data-language' ),
			line_value = editor.dom.getAttrib( parent_node, 'data-line' ) || 0,
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
				console.log();
				
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
				if ( !! lang )
					editor.contentStyles.push( 'pre[data-language="'+lang+'"]:before { content:"'+wprainbow.languages[i].text+'" }' );
			}
		}
		
	});

	if ( wprainbow.enable_line_numbering ) {
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
	}
	
} );
