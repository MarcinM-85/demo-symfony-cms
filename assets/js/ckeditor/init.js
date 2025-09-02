import { Alignment } from '@ckeditor/ckeditor5-alignment';
import { Bold, Code, Italic, Strikethrough, Subscript, Superscript, Underline } from '@ckeditor/ckeditor5-basic-styles';
import { BlockQuote } from '@ckeditor/ckeditor5-block-quote';
import { Bookmark } from '@ckeditor/ckeditor5-bookmark';
import { CodeBlock } from '@ckeditor/ckeditor5-code-block';
import { ClassicEditor } from '@ckeditor/ckeditor5-editor-classic';
import { Essentials } from '@ckeditor/ckeditor5-essentials';
import { FindAndReplace } from '@ckeditor/ckeditor5-find-and-replace';
import { Font } from '@ckeditor/ckeditor5-font';
import { Fullscreen } from '@ckeditor/ckeditor5-fullscreen';
import { Heading } from '@ckeditor/ckeditor5-heading';
import { HorizontalLine } from '@ckeditor/ckeditor5-horizontal-line';
import { GeneralHtmlSupport, HtmlComment } from '@ckeditor/ckeditor5-html-support';
import { Image, ImageCaption, ImageInsert, ImageStyle, ImageTextAlternative, ImageToolbar, ImageUpload, AutoImage } from '@ckeditor/ckeditor5-image';
import { Indent, IndentBlock } from '@ckeditor/ckeditor5-indent';
import { AutoLink, Link, LinkImage } from '@ckeditor/ckeditor5-link';
import { AdjacentListsSupport, List, TodoList } from '@ckeditor/ckeditor5-list';
import { MediaEmbed } from '@ckeditor/ckeditor5-media-embed';
//import { Minimap } from '@ckeditor/ckeditor5-minimap';
import { Paragraph } from '@ckeditor/ckeditor5-paragraph';
import { RemoveFormat } from '@ckeditor/ckeditor5-remove-format';
import { ShowBlocks } from '@ckeditor/ckeditor5-show-blocks';
import { SourceEditing } from '@ckeditor/ckeditor5-source-editing';
import { SpecialCharacters, SpecialCharactersEssentials  } from '@ckeditor/ckeditor5-special-characters';
//import { SimpleUploadAdapter } from '@ckeditor/ckeditor5-upload';
import { EditorWatchdog } from '@ckeditor/ckeditor5-watchdog';
import { WordCount } from '@ckeditor/ckeditor5-word-count';
import { FileGatorPlugin } from './plugin/FileGatorPlugin.js';

//import '@ckeditor/ckeditor5-theme-lark';

const LICENSE_KEY = 'GPL';

const CKEditorList = document.querySelectorAll('.CKEditor');
if( CKEditorList.length>0 ) {
    CKEditorList.forEach((CKEditor) => {
        const Watchdog = new EditorWatchdog( ClassicEditor );

        Watchdog.setCreator( async ( editorSelector, editorConfig ) => {
            try {
                const editor = await ClassicEditor.create( editorSelector, editorConfig );

                // Przykładowe operacje po utworzeniu edytora
                editor.execute( 'showBlocks' );
                window.editor = editor;

                return editor;
            } catch ( error ) {
                console.error( 'CKEditor error:', error );
                throw error; // przekazuje dalej błąd do Watchdog
            }
        } );

        Watchdog
            .create( CKEditor, {
                licenseKey: LICENSE_KEY,
                plugins: [ AdjacentListsSupport, Alignment, AutoLink, Bookmark, BlockQuote, Bold, Code, CodeBlock, EditorWatchdog, Essentials, FindAndReplace, Font, Fullscreen, GeneralHtmlSupport, Heading, HorizontalLine, HtmlComment, Image, ImageCaption, ImageInsert, ImageStyle, ImageTextAlternative, ImageToolbar, ImageUpload, Indent, IndentBlock, Italic, Link, LinkImage, List, MediaEmbed, Paragraph, RemoveFormat, ShowBlocks, SourceEditing, SpecialCharacters, SpecialCharactersEssentials, Strikethrough, Subscript, Superscript, TodoList, Underline, WordCount ],
                extraPlugins: [FileGatorPlugin],
                toolbar: {
                    items: [
                        'undo', 'redo', 'findAndReplace', '|', 
                        'heading', '|', 
                        {
                            label: 'HTML [Block]',
                            withText: true,
                            icon: false,
                            items: [ 'alignment', 'bulletedList', 'numberedList', 'todoList', 'indent', 'outdent', 'blockQuote', 'codeBlock', 'horizontalLine' ]
                        }, '|', 
                        {
                            label: 'HTML [Inline]',
                            withText: true,
                            icon: 'bold',
                            items: [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'specialCharacters', 'removeFormat' ]
                        }, '|', 
                        {
                            label: 'Font',
                            withText: true,
                            icon: 'text',
                            items: [ 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor' ]
                        }, '|', 
                        {
                            label: 'Link',
                            withText: true,
                            icon: false,
                            items: [ 'link', 'bookmark' ]
                        }, '|', 
                        {
                            label: 'Media',
                            withText: true,
                            icon: false,
                            items: ['insertImage', 'FileGator',  'mediaEmbed' ]
                        }, '|', 
                        'showBlocks', 'sourceEditing', 'fullscreen'
                    ],
                    shouldNotGroupWhenFull: true
                },
                menuBar: {
                    isVisible: true
                },
                alignment: {
                    options: [ 'left', 'right', 'center', 'justify' ]
                },
    //            findAndReplace: {
    //                uiType: 'dropdown'
    //            },
    //            minimap: {
    //                container: CKEditor.parentElement.querySelector( '.cke-minimap-container' )
    //            },
                fontColor: {
                    columns: 6,
                    documentColors: 6,
                    colorPicker: {
                        format: 'hex'
                    }
                },
                fontBackgroundColor: {
                    columns: 6,
                    documentColors: 3,
                    colorPicker: false
                },
                fullscreen: {
                    menuBar: {
                        isVisible: false
                    },
    //                onEnterCallback: () => console.log('ENTER FULLSCREEN') ,
    //                onLeaveCallback: function(){ console.log('LEAVE FULLSCREEN') }
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'division', view: 'div', title: 'Division', class: 'ck-heading_division' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' },
                        { model: 'address', view: 'address', title: 'Address', class: 'ck-heading_address' },
                        { model: 'blockquote', view: 'blockquote', title: 'Blockquote', class: 'ck-heading_blockquote' }
                    ]
                },
                htmlSupport: {
                    allow: [  ],
                    disallow: [  ]
                },
                image: {
                    insert: {
                        integrations: [ 'url'/*, 'filegator'*/ ],
                        type: 'auto'
                    },
                    toolbar: [
                        'imageStyle:block',
                        'imageStyle:side',
                        '|',
                        'toggleImageCaption',
                        'imageTextAlternative',
                        '|',
                        'linkImage'
                    ],
                },
                link: {
                    toolbar: [ 'linkPreview', '|', 'editLink', 'linkProperties', 'unlink' ],
                    decorators: {
                        openInNewTab: {
                            mode: 'manual',
                            label: 'Open in a new tab',
                            attributes: {
                                target: '_blank',
                                rel: 'noopener noreferrer'
                            }
                        }, 
                        isButton: {
                            mode: 'manual',
                            label: 'Styl: Przycisk',
                            attributes: {
                                class: 'btn btn-primary'
                            }
                        },
                        isMuted: {
                            mode: 'manual',
                            label: 'Styl: Link wyciszony',
                            attributes: {
                                class: 'link-muted'
                            }
                        }
                    }
    //                addTargetToExternalLinks: true,
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                mediaEmbed: {
                    removeProviders: [ 'instagram', 'twitter', 'googleMaps', 'flickr', 'facebook' ],
    //                providers: [
    //                    {
    //                        url: /^example\.com\/media\/(\w+)/,
    //
    //                        html: match => '...'
    //                    },
    //                ]
                },
    //            simpleUpload: {
    //            },
                specialCharacters: {
                    order: [
                        'Text',
                        'Latin',
                        'Mathematical',
                        'Currency',
                        'Arrows'
                    ]
                },
    //            wordCount: {
    //            }
            })
            .catch(error => {
                console.error('Watchdog error:', error);
            });
    });
}