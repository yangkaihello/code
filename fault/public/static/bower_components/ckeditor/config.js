/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.toolbar = 'Full';
    
    config.toolbar_Full = [
       ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
       ['EasyImageUpload','TextColor','-','FontSize'],
    ];

    config.width = 400;
    
};
