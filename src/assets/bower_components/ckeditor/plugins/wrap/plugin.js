CKEDITOR.plugins.add('wrap', {
    icons: 'columns',
    init: function (editor) {
        editor.ui.addButton('columns', {
            icon: this.path + 'icons/column.png',
            label: 'Divide text into 2 columns',
            command: 'wrap',
            toolbar: 'insert',
        });
        editor.addCommand('wrap', {
            exec : function( editor ) {
                range = editor.getSelection().getRanges()[ 0 ],
                el = editor.document.createElement( 'div' );
                el.setAttributes({class: 'kj_2cols  mb-5'})          

                el.append(range.cloneContents());                        
                editor.insertElement(el);                            
            }
        });
    }
});

