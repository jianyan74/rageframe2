/*
* A Math editor plugin for CKEditor.
*
* Uses MathQuill ( http://mathquill.com/ ) to provide an editable WYSIWYG-latex field
* in a CKEditor-dialog and CodeCogs ( http://www.codecogs.com/ ) to generate pictures
* from the latex values.
*
* Original icon by David Vignoni ( http://icones.pro/en/edu-mathematics-png-image.html )
* and licensed under LGPL.
*
* Implementation is inspired by Vaadin-RichMathArea
* ( https://github.com/ripla/VMathQuill ) and CodeCogs Equation Editor CKEditor -plugin
* ( http://ckeditor.com/addon/equation ). The main difference between this plugin and the
* 'original' CodeCogs plugin is that this plugin aims to integrate more smoothly with
* CKEditor.
*
* Special thanks also to creators of great CKEditor -plugin tutorials:
*  http://docs.cksource.com/CKEditor_3.x/Tutorials/Abbr_Plugin_Part_1 and
*  http://docs.cksource.com/CKEditor_3.x/Tutorials/Abbr_Plugin_Part_2
*
* Author: Riku Haavisto
* License: GPL, LGPL and MPL
*/
(function() {
'use strict';
var pluginName = 'mathedit',
	pluginCmd = 'matheditDialog',
	inputFieldId = 'mathedit-latex-input-field',
	mathImgClass = 'mathImg',
	runningId = 0;
CKEDITOR.plugins.add( pluginName, {
	init: function ( editor ) {
		var iconPath = this.path + 'icons/mathedit.png';
		// register dialog-command
		editor.addCommand( pluginCmd,
			new CKEDITOR.dialogCommand(pluginCmd, {
			// basic ACF-integration
			allowedContent: 'img[src,title,class](mathImg)',
			requiredContent: 'img[src,title,class](mathImg)'
		}));
		editor.ui.addButton( pluginName, {
			label : 'Insert math',
			command : pluginCmd,
			toolbar: 'insert',
			icon: iconPath
		});
		// add context-menu entry
		if ( editor.contextMenu ) {
			editor.addMenuGroup( 'Math' );
			editor.addMenuItem( pluginName, {
				label: 'Edit function',
				icon: iconPath,
				command: pluginCmd,
				group: 'Math'
			});
			// if the selected item is image of class 'mathImg',
			// we shold be interested in it
			editor.contextMenu.addListener( function(element) {
				var res = {};
				if ( element ) {
					element = element.getAscendant( 'img', true );
				}
				if ( element && ! element.data('cke-realelement') &&
					element.getAttribute('class') === mathImgClass ) {
					res[pluginName] = CKEDITOR.TRISTATE_OFF;
					return res;
				}
			});
		}

		// add listeners to allow editing the inserted math-images
		// same test as in context-menu; add to high priority (1)
		editor.on( 'doubleclick', function(evt) {
			var element = evt.data.element;
			if (element && element.is('img')) {
				if ( element.getAttribute('class') === mathImgClass ) {
				   evt.data.dialog = pluginCmd;
				   evt.cancelBubble = true;
				   evt.returnValue = false;
				   evt.stop();
				}
			}
		}, null, null, 1);

		// add dialog for handling the math-input
		// TODO: this could maybe look better; also integrating a latex-toolbar would be useful
		CKEDITOR.dialog.add( pluginCmd, function( editor )
		{
			var currId = inputFieldId + "-" + runningId;
			runningId += 1;
			return {
				title : 'Add Math',
				minWidth : 400,
				minHeight : 200,
				contents : [
					{
					// Definition of the Settings dialog window tab (page)
					// Even though there is only one tab it should be named
						id : 'general',
						label : 'Settings',
						elements :
						[
							{
								// everything is done on single html-element
								// as MathQuill-functionality requires direct access
								// to required element
								// TODO: some other solution than hard-coded id?
								type : 'html',
								html : "<span id='" + currId + "' style='border: 1px solid gray;" +
    								"padding: 2px;'></span>" +
									"<div style='float: right; padding-top: 10px;'>" +
									"<a href='http://www.codecogs.com/' style='cursor: " +
									"pointer; font-size: smaller; color:#085585' " +
									"target='_blank'>CodeCogs</a><br/>" +
									"<a href='http://www.mathquill.com/' style='cursor: pointer; " +
									"font-size: smaller; color:#085585' target='_blank'>MathQuill</a>" +
									"</div>",
								setup : function( image ) {
									// $ accesses the actual DOM-element
									var dialEl = jQuery(CKEDITOR.document.getById(currId).$);
									dialEl.mathquill('editable');
									dialEl.mathquill('latex', image.getAttribute('title'));
								},
								commit : function ( image ) {
									var dialEl = jQuery(CKEDITOR.document.getById(currId).$),
										url='http://latex.codecogs.com/gif.latex?',
										latex = '';
									latex = dialEl.mathquill('latex');
									if ( ! latex ) {
										latex = 'empty';
									}
									url += escape(latex);
									// codedogs produces gif-images based on GET params
									image.setAttribute('src', url);
									// store actual latex as title
									image.setAttribute('title', latex);
								}
							}
						]
					}
				],
				onShow : function(event) {
					var dialog = this,
						sel = editor.getSelection(),
						image = sel.getStartElement();

					// find out if there is a selected img
					// that we might be interested in;
					// if not create new

					if ( image ) {
						image = image.getAscendant( 'img', true );
					}

					if ( !image || image.getAttribute('class') !== mathImgClass || image.data( 'cke-realelement' ) )
					{
						image = editor.document.createElement( 'img' );
						image.setAttribute('class', mathImgClass);
						// add some initial-value
						image.setAttribute('title', 'x^2');
						dialog.insertMode = true;
					}
					else {
						dialog.insertMode = false;
					}
					dialog.image = image;

					// set-up the field values based on selected or newly created image
					dialog.setupContent( dialog.image );
				},
				onOk : function() {
					var dialog = this,
						image = dialog.image;

					// insert new element if in insert-mode
					if ( dialog.insertMode ) {
						editor.insertElement( image );
					}

					// Populate the element with correct values
					dialog.commitContent( image );
				}
			};
		} );
	}
});
})();
