//Google QR-Code generator plugin by zmmaj from zmajsoft-team
//blah... version 1.0.
//problems? write to zmajsoft@zmajsoft.com

// Register a new CKEditor plugin.
// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.resourceManager.html#add
CKEDITOR.plugins.add( 'qrc',
{
	// The plugin initialization logic goes inside this method.
	// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.pluginDefinition.html#init
	init: function( editor )
	{
		// Create an editor command that stores the dialog initialization command.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.command.html
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialogCommand.html
		editor.addCommand( 'qrc', new CKEDITOR.dialogCommand( 'qrc' ) );

		// Create a toolbar button that executes the plugin command defined above.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.html#addButton
		editor.ui.addButton( 'qrc',
		{
			// Toolbar button tooltip.
			label: 'Insert a ZS Google QR-Code picture',
			// Reference to the plugin command name.
			command: 'qrc',
			// Button's icon file path.
			icon: this.path + 'images/qrc.png'
		} );

		// Add a new dialog window definition containing all UI elements and listeners.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html#.add
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.dialogDefinition.html
		CKEDITOR.dialog.add( 'qrc', function( editor )
		{
			return {
				// Basic properties of the dialog window: title, minimum size.
				// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.dialogDefinition.html
				title : 'ZmajSoft QR-Code Picture generator',
				minWidth : 400,
				minHeight : 200,
				// Dialog window contents.
				// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.definition.content.html
				contents :
				[
					{
						// Definition of the Settings dialog window tab (page) with its id, label and contents.
						// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.contentDefinition.html
						id : 'general',
						label : 'Settings',
						elements :
						[
							// Dialog window UI element: HTML code field.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.html.html
							{
								type : 'html',
								// HTML code to be shown inside the field.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.html.html#constructor
								html : 'This dialog window lets you create and embed into text simple Google QR-Code Picture. '
							},
							// Dialog window UI element: a text input field for the Latitude.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.textInput.html
							{
								type : 'text',
								id : 'txt',
								label : 'Enter ANY text, code or mix',
								validate : CKEDITOR.dialog.validate.notEmpty( 'Can NOT be empty.' ),
								required : true,
								commit : function( data )
								{
									data.txt = this.getValue();
								}
							},
							// entyer here picture size
														{
								type : 'text',
								id : 'siz',
								label : 'Enter picture size ( eg 300)',
								validate : CKEDITOR.dialog.validate.notEmpty( 'Can NOT be empty.' ),
								required : true,
								commit : function( data )
								{
									data.siz= this.getValue();
								}
							},


		                 // Dialog window UI element: HTML code field.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.html.html
							{
								type : 'html',
								// HTML code to be shown inside the field.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.html.html#constructor
								html : 'If you have problems Email to zmajsoft@zmajsoft.com </br> <a href="www.zmajsoft.com" target="_blank">zmmaj</a> from zmajSoft-team'
							}
						]
					}
				],
				onOk : function()
				{
					// Create a link element and an object that will store the data entered in the dialog window.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.document.html#createElement
					var dialog = this,
						data = {},
						link = editor.document.createElement( 'a' );
					// Populate the data object with data entered in the dialog window.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html#commitContent
					this.commitContent( data );

					// Set the URL (href attribute) of the link element.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#setAttribute


					// Insert the link element into the current cursor position in the editor.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.editor.html#insertElement
					editor.insertHtml('<img src="https://chart.googleapis.com/chart?cht=qr&chs='+data.siz+'x'+data.siz+ '&chl='+data.txt+'&choe=UTF-8 &chld=H |4"/>');
				}
			};
		} );
	}
} );
