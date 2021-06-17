/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    config.extraPlugins = 'imagepaste';
    config.extraPlugins = 'mathjax';
    config.extraPlugins = 'pastefromexcel';
    config.extraPlugins = 'tableresize';
    config.extraPlugins = 'uploadimage';
    config.extraPlugins = 'widget';
    config.extraPlugins = 'widgetselection';
    config.extraPlugins = 'wordcount,notification';
    config.extraPlugins = 'lineutils';
    config.extraPlugins = 'clipboard';
    config.extraPlugins = 'eqneditor';
    config.extraPlugins = 'autocorrect';
    config.extraPlugins = 'tabletoolstoolbar';
    config.extraPlugins = 'chart';
    config.skin = 'office2013';
    config.toolbarGroups = [
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
        {name: 'forms', groups: ['forms']},
        '/',
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
        {name: 'links', groups: ['links']},
        {name: 'insert', groups: ['insert']},
        '/',
        {name: 'styles', groups: ['styles']},
        {name: 'colors', groups: ['colors']},
        {name: 'tools', groups: ['tools']},
        {name: 'others', groups: ['others']},
        {name: 'about', groups: ['about']}
    ];

    config.removeButtons = 'Source,Save,NewPage,Print,Templates,Preview,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Language,BidiRtl,BidiLtr,Anchor,Unlink,Link,Iframe,PageBreak,ShowBlocks,Maximize,About,Blockquote,CopyFormatting,RemoveFormat,Flash,HorizontalRule,Smiley,Image';


    config.wordcount = {
        // Whether or not you want to show the Paragraphs Count
        showParagraphs: true,
        // Whether or not you want to show the Word Count
        showWordCount: true,
        // Whether or not you want to show the Char Count
        showCharCount: true,
        // Whether or not you want to count Spaces as Chars
        countSpacesAsChars: false,
        // Whether or not to include Html chars in the Char Count
        countHTML: false,
        // Maximum allowed Word Count, -1 is default for unlimited
        maxWordCount: -1,
        // Maximum allowed Char Count, -1 is default for unlimited
        maxCharCount: -1,
        // Add filter to add or remove element before counting (see CKEDITOR.htmlParser.filter), Default value : null (no filter)
        filter: new CKEDITOR.htmlParser.filter({
            elements: {
                div: function (element) {
                    if (element.attributes.class == 'mediaembed') {
                        return false;
                    }
                }
            }
        })
    };
    config.removePlugins='elementspath';
    config.resize_enabled=false;
};
