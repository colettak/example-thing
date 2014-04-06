<?php
/*
Plugin Name: Example Thing!
Description: How to make a TinyMCE View
Author: wonderboymusic
Author URI: http://profiles.wordpress.org/wonderboymusic/
Version: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/



add_action( 'admin_head', 'fb_add_tinymce' );
function fb_add_tinymce() {
    global $typenow;

    // only on Post Type: post and page
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return ;

    add_filter( 'mce_external_plugins', 'fb_add_tinymce_plugin' );
    // Add to line 1 form WP TinyMCE
    add_filter( 'mce_buttons', 'fb_add_tinymce_button' );
}

// inlcude the js for tinymce
function fb_add_tinymce_plugin( $plugin_array ) {

    $plugin_array['fb_test'] = plugins_url( '/example-thing.js', __FILE__ );
    return $plugin_array;
}

// Add the button key for address via JS
function fb_add_tinymce_button( $buttons ) {

    array_push( $buttons, 'fb_test_button_key' );
    // Print all buttons
    return $buttons;
}




class ExampleThing {
	static $instance;

	public static function get_instance() {
		if ( ! self::$instance instanceof ExampleThing  ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'load-post.php', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'load-post-new.php', array( $this, 'admin_enqueue_scripts' ) );
	}

	function admin_enqueue_scripts() {
		add_action( 'admin_footer', array( $this, 'print_templates' ) );
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );
		$src = plugins_url( 'example-thing.js', __FILE__ );
		wp_enqueue_script( 'example-thing', $src, array( 'mce-view' ), false, 1 );
	}

	
	function action_admin_init() {
		// only hook up these filters if we're in the admin panel, and the current user has permission
		// to edit posts and pages
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
		}
	}
	
	function filter_mce_button( $buttons ) {
		// add a separation before our button, here our button's id is "mygallery_button"
		array_push( $buttons, '|', 'mygallery_button' );
		return $buttons;
	}
	
	function print_templates() {
	?>
	<script type="text/html" id="tmpl-thing-details">
		<div class="media-embed">
			<div class="embed-media-settings">
				<label class="setting">
					<span><?php _e( 'Name' ); ?></span>
					<input type="text" data-setting="name" value="{{ data.name }}" />
				</label>
				<label class="setting">
					<span><?php _e( 'Favorite Color' ); ?></span>
					<input type="text" data-setting="color" value="{{ data.color }}" />
				</label>
				<label class="setting">
					<span><?php _e( 'Favorite Food' ); ?></span>
					<input type="text" data-setting="food" value="{{ data.food }}" />
				</label>
			</div>
		</div>
	</script>


	<script type="text/html" id="tmpl-editor-thing">
	<div style="background:gray; height:100%;padding:1em;">
		<div class="toolbar">
			<div class="dashicons dashicons-edit edit"></div>
			<div class="dashicons dashicons-no-alt remove"></div>
		</div>
		<p>Name: {{ data.name }}<br/>Favorite Color: {{ data.color }}<br/>Favorite Food: {{ data.food }}</p>
	</div>
	</script>
	<?php
	}
}
ExampleThing::get_instance();
