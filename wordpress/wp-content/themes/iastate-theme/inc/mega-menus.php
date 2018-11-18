<?php
function isu_mega_menu_custom_post_type() {
	$labels = array(
		'name'               => _x( 'Mega Menus', 'post type general name', 'iastate-theme' ),
		'singular_name'      => _x( 'Mega Menu', 'post type singular name', 'iastate-theme' ),
		'menu_name'          => _x( 'Mega Menus', 'admin menu', 'iastate-theme' ),
		'name_admin_bar'     => _x( 'Mega Menu', 'add new on admin bar', 'iastate-theme' ),
		'add_new'            => _x( 'Add Menu', 'menu', 'iastate-theme' ),
		'add_new_item'       => __( 'Add New Menu', 'iastate-theme' ),
		'new_item'           => __( 'New Menu', 'iastate-theme' ),
		'edit_item'          => __( 'Edit Menu', 'iastate-theme' ),
		'view_item'          => __( 'View Menu', 'iastate-theme' ),
		'all_items'          => __( 'All Menus', 'iastate-theme' ),
		'search_items'       => __( 'Search Menus', 'iastate-theme' ),
		'parent_item_colon'  => __( 'Parent Menus:', 'iastate-theme' ),
		'not_found'          => __( 'No menus found.', 'iastate-theme' ),
		'not_found_in_trash' => __( 'No menus found in Trash.', 'iastate-theme' )
	);

	$args = array(
		'labels'              => $labels,
		'description'         => __( 'Mega Menu for the IASTATE Theme.', 'iastate-theme' ),
		'public'              => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'show_in_nav_menus'   => true,
		'show_ui'             => true,
		'show_in_admin_bar'   => false,
		'menu_position'       => 60,
		'menu_icon'           => 'dashicons-welcome-widgets-menus',
		'supports'            => array( 'title', 'editor', ),
		'map_meta_cap'        => true,
		'capability_type'     => 'post',

	);
	register_post_type( 'isu_mega_menu', $args );
}

add_action( 'init', 'isu_mega_menu_custom_post_type', 0 );

function isu_mega_menu_custom_post_type_help_tab() {

	$screen = get_current_screen();

	if ( 'isu_mega_menu' != $screen->post_type ) {
		return;
	}

	$args = array(
		'id'      => 'isu_mega_menu',
		'title'   => 'Usage',
		'content' => <<<'HTML'
<h3>Using Mega Menus</h3>
<p>Mega Menus are a simple post type that just has a title and content. The title will appear as the menu title
and anything in the content will show in the Mega Menu content area</p>
<p>To use the Mega Menus feature, start by creating a new menu under Mega Menus->Add Menu.</p>
<p>Once you've created a Mega Menu item, you can add it to the menu. Mega Menus will only appear under the 
<strong>Primary</strong> menu area. In the menu editor select your <strong>Primary</strong> menu and click on the 'Mega
Menus' post type. There you can see any of the Mega Menus you have created. Then just select or drag and drop the
item where you want it to show in the menu. Save the menu and visit you site to see the menu in action</p>
<p>To change the content of the Mega Menu, go back to the Mega Menu section and edit the post.</p>
HTML
	);

	$screen->add_help_tab( $args );
}

add_action( 'admin_head', 'isu_mega_menu_custom_post_type_help_tab' );


/**
 * Register meta box(es).
 */
function isu_mega_menu_register_meta_boxes() {
	add_meta_box( 'isu_mega_menu-uri', __( 'Mega Menu Options', 'iastate-theme' ), 'isu_mega_menu_options_display_callback', 'isu_mega_menu', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'isu_mega_menu_register_meta_boxes' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function isu_mega_menu_options_display_callback( $post ) {
	$nonce           = wp_nonce_field( 'isu_mega_menu_options_action', 'isu_mega_menu_nonce', true, false );
	$value           = get_post_meta( $post->ID, 'isu_mega_menu_uri', true );
	$uri_label       = __( 'URI', 'iastate-theme' );
	$uri_description = __( 'Link for Mega Menu if you want the menu to link to a page.', 'iastate-theme' );

	echo <<<HTML
{$nonce}
<p class="post-attributes-label-wrapper">
	<label class="post-attributes-label" for="isu_mega_menu_uri">{$uri_label}</label>
	<input name="isu_mega_menu_uri" type="url" id="isu_mega_menu_uri" value="{$value}">
</p>
<p class="description" id="isu_mega_menu_uri-description">{$uri_description}</p>
HTML;
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function isu_mega_menu_save_meta_box( $post_id ) {
	$nonce_name   = isset( $_POST['isu_mega_menu_nonce'] ) ? $_POST['isu_mega_menu_nonce'] : '';
	$nonce_action = 'isu_mega_menu_options_action';

	if ( ! isset( $nonce_name ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
		return;
	}
	if ( 'isu_mega_menu' !== $_POST['post_type'] ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	$uri = sanitize_text_field( $_POST['isu_mega_menu_uri'] );
	update_post_meta( $post_id, 'isu_mega_menu_uri', $uri );
}

add_action( 'save_post', 'isu_mega_menu_save_meta_box' );