(function() {
	tinymce.PluginManager.add('gbox_button', function( editor, url ) {
		editor.addButton( 'gbox_button', {
			text: '',
			icon: 'wa-homepage',
			type: 'menubutton',
			menu: [
					{
						text: 'Select your gallery',
						onclick: function() {
							editor.windowManager.open( {
							title: 'Insert your gallery shortcode',
							body: [
								{
									type: 'listbox',
									name: 'select_Gallery',
									label: 'select Gallery',
									values: gbox_post_id
								}
							
								
									],
									onsubmit: function( e ) {
										editor.insertContent(e.data.select_Gallery );
									}
								});
							}
						}
						
			]
		});
	});
})();
