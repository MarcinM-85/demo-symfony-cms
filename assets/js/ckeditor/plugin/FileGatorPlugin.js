import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview';
import FileGatorIcon from '../icon/file-gator-icon.svg';

export class FileGatorPlugin extends Plugin {
    init() {
        const editor = this.editor;

        editor.ui.componentFactory.add('FileGator', () => this.#_createToolbarButton() );

        // Rejestracja integracji z image.insert
        if ( editor.plugins.has( 'ImageInsertUI' ) ) {
                editor.plugins.get( 'ImageInsertUI' ).registerIntegration( {
                        name: 'filegator',
                        observable: () => editor.commands.get( 'imageInsert' ),
                        buttonViewCreator: () => this.#_createToolbarButton(),
                        formViewCreator: () => this.#_createDropdownButton(),
//                        menuBarButtonViewCreator: isOnly => this.#_createMenuBarButton( isOnly ? 'insertOnly' : 'insertNested' )
                } );
        }
    }

    handleMessage(event) {
        const editor = this.editor;

        if (event.origin !== window.location.origin) return;

        const fileUrl = event.data?.fileUrl;
        if (fileUrl) {
            
            editor.model.change(writer => {
                if (/\.(jpg|jpeg|png|gif|webp|svg)$/i.test(fileUrl)) {
                    const imageElement = writer.createElement('imageBlock', { src: '/repository' + fileUrl });
                    editor.model.insertContent(imageElement, editor.model.document.selection);
                } else {
                    const linkText = fileUrl.split('/').pop();
                    const textNode = writer.createText(linkText, { linkHref: '/repository' + fileUrl });
                    editor.model.insertContent(textNode, editor.model.document.selection);
                }
            });
        }
    };

    #_createButton() {
            const editor = this.editor;
            const locale = editor.locale;
            const command = editor.commands.get( 'imageInsert' );

            const view = new ButtonView(this.editor.locale);

            view.set({
                label: 'Wstaw przez FileGator',
                icon: FileGatorIcon,
                tooltip: true,
                withText: true
            });

            view.on('execute', () => {
                const fileWindow = window.open('/filegator/', 'FileGator', 'popup=true,width=800,height=600');

                window.addEventListener('message', this.handleMessage, { once: true });
            });

            // Powiazanie wartosci isEnabled przycisku z isEnabled dla command [czyli imageInsert] // Zmiana isEnabled w imageInsert powoduje zmiane idEnabled przycisku.
            view.bind( 'isEnabled' ).to( command );

            return view;
    }
        
    #_createToolbarButton() {
            const t = this.editor.locale.t;
            const imageInsertUI = this.editor.plugins.get( 'ImageInsertUI' );
            const uploadImageCommand = editor.commands.get( 'imageInsert' );

            const button = this.#_createButton( );
            button.withText = false;

//            button.bind( 'label' ).to(
//                    imageInsertUI,
//                    'isImageSelected',
//                    uploadImageCommand,
//                    'isAccessAllowed',
//                    ( isImageSelected, isAccessAllowed ) => {
//                            if ( !isAccessAllowed ) {
//                                    return t( 'You have no image upload permissions.' );
//                            }
//
//                            return isImageSelected ? t( 'Replace image from computer' ) : t( 'Upload image from computer' );
//                    }
//            );

            return button;
    }

    #_createDropdownButton() {
            const t = this.editor.locale.t;
            const imageInsertUI = this.editor.plugins.get( 'ImageInsertUI' );

            const button = this.#_createButton( );

//            button.bind( 'label' ).to(
//                    imageInsertUI,
//                    'isImageSelected',
//                    isImageSelected => isImageSelected ? t( 'Replace from computer' ) : t( 'Upload from computer' )
//            );
//
//            button.on( 'execute', () => {
//                    imageInsertUI.dropdownView.isOpen = false;
//            } );

            return button;
    }

    #_createMenuBarButton( type ) {
            const t = this.editor.locale.t;
            const button = this.#_createButton( );

            switch ( type ) {
                    case 'standalone':
                            button.label = t( 'Image from computer' );
                            break;
                    case 'insertOnly':
                            button.label = t( 'Image' );
                            break;
                    case 'insertNested':
                            button.label = t( 'From computer' );
                            break;
            }

            return button;
    }
}