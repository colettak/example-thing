( function() {
    tinymce.PluginManager.add( 'fb_test', function( editor, url ) {

        // Add a button that opens a window
        editor.addButton( 'fb_test_button_key', {

            text: 'FB Test Button',
            icon: false,
            onclick: function() {
                // Open window
                editor.windowManager.open( {
                    title: 'Example plugin',
                    body: [{
                        type: 'textbox',
                        name: 'title',
                        label: 'Title'
                    }],
                    onsubmit: function( e ) {
                        // Insert content when the window form is submitted
                        editor.insertContent( '[thing] ' + e.data.title );
                    }

                } );
            }

        } );

    } );

} )();