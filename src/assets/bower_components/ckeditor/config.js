/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert',       groups:['insert', 'media'] },
		{ name: 'insert',       groups:['insert', 'wrap'] },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   	groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', 		groups: ['simplebutton'] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';
	config.imageBrowser_listUrl = app.ajax_url+'/admin/media/json';
	config.contentsCss = '../../../gaiff/css/fonts.css';
	config.font_names = 'Lato Bold/lato-bold;Lato Bold Italic/lato-bold-italic;Lato Black/lato-black;Lato Regular/lato-regular;Lato Italic/lato-italic;Lato Light/lato-light;Playfair Bold/playfair-bold;Playfair Regular/playfair-regular;Sourcesans Semibold/sourcesans-semibold;DejaVu Sans Condensed/DejaVu Sans Condensed;DejaVu Sans Condensed Bold/DejaVu Sans Condensed Bold;Bodoni/Bodoni;Bodoni-Bold/Bodoni-Bold';
	// Set the most common block elements.
	// config.colorButton_enableMore = '#3e59ae';
	config.colorButton_colors = '222,d7af37,d00';
	config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre';
	config.extraPlugins = 'btgrid,font,image2,justify,colorbutton,colordialog,embedsemantic,iframe,smiley,letterspacing,lineheight,texttransform,liststyle,simplebutton,media,wrap';
	config.allowedContent = true;

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
};
