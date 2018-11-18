<?php
/**
 * Hide WP version in html
 *
 * @return string
 */
function isu_remove_wp_version() {
	return '';
}

add_filter( 'the_generator', 'isu_remove_wp_version' );

/**
 * @param $class
 *
 * @return string
 */
function add_image_class( $class ) {
	$class .= ' img-responsive';

	return $class;
}

add_filter( 'get_image_tag_class', 'add_image_class' );

/**
 * Registers an editor stylesheet for the theme.
 */
function wpdocs_theme_add_editor_styles() {
	add_editor_style( 'iastate/css/iastate.min.css' );
}

add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );

if ( ! function_exists( 'yourtheme_excerpt_more' ) && ! is_admin() ) :
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a 'Continue reading' link.
	 *
	 * @since Your Theme 1.0
	 *
	 * @return string 'Continue reading' link prepended with an ellipsis.
	 */
	function yourtheme_excerpt_more( $more ) {
		$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
			esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Name of current post */
			sprintf( __( 'Continue reading %s', 'iastate-theme' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
		);

		return ' &hellip; ' . $link;
	}

	add_filter( 'excerpt_more', 'yourtheme_excerpt_more' );
endif;


// Allow shortcodes in widget text field
add_filter( 'widget_text', 'do_shortcode' );


/**
 * This function adds a meta box with a callback function of my_metabox_callback()
 */
function iastate_theme_meta_boxes() {
	add_meta_box(
		'iastate_theme_display',
		__( 'Theme Display', 'iastate-theme' ),
		'iastate_hide_title_metabox_callback',
		array( 'post', 'page' ),
		'normal',
		'default'
	);
}

/**
 * Get post meta in a callback
 *
 * @param WP_Post $post The current post.
 * @param array $metabox With metabox id, title, callback, and args elements.
 */
function iastate_hide_title_metabox_callback( $post, $metabox ) {
	wp_nonce_field( 'iastate_theme_display_nonce', 'isu_meta_box_nonce' );
	$visibility = get_post_meta( $post->ID, 'title_visibility', true );
	//$post_nav = get_post_meta( $post->ID, 'post_nav', true );
	?>
    <p>
        <input type="checkbox" id="title_visibility" name="title_visibility" <?php checked( $visibility, 'hide' ); ?>>
        <label for="title_visibility">Hide Title</label>
    </p>
	<?php
}

function iastate_meta_box_display_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post' ) ) {
		return;
	}
	if ( ! isset( $_POST['isu_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['isu_meta_box_nonce'], 'iastate_theme_display_nonce' ) ) {
		return;
	}

	$visChk = isset( $_POST['title_visibility'] ) ? 'hide' : 'show';
	update_post_meta( $post_id, 'title_visibility', $visChk );
	if(isset($_POST['post_nav'])){
		update_post_meta( $post_id, 'post_nav', $_POST['post_nav'] );
    }
}

add_action( 'add_meta_boxes_post', 'iastate_theme_meta_boxes' );
add_action( 'add_meta_boxes_page', 'iastate_theme_meta_boxes' );
add_action( 'save_post', 'iastate_meta_box_display_save' );

/**
 * Auto-set footer widgets if nothing is set. Usually this means it's just been activated
 *
 * @param string $old_theme_name
 * @param WP_Theme $old_theme
 */
function isu_set_default_theme_widgets($old_theme_name, $old_theme = null)
{
    $widgetContact = get_option('widget_isu_footer_contact');
    $widgetAssociate = get_option('widget_isu_footer_associates');
    $widgetLegal = get_option('widget_isu_footer_legal');
    $widgetSocial = get_option('widget_isu_footer_social');

    if (!is_int(key($widgetContact)) && !is_int(key($widgetAssociate)) &&
        !is_int(key($widgetLegal)) && !is_int(key($widgetSocial))) {

        $widgetContact[2]['footer_contact'] = iastate_theme()->getOption('footer_contact');
        $widgetAssociate[2]['footer_associates'] = iastate_theme()->getOption('footer_associates');
        $widgetLegal[2] = array();
        $widgetSocial[2]['footer_social'] = iastate_theme()->getOption('footer_social');
        $widgetSocial[2]['show_social_labels'] = iastate_theme()->getOption('show_social_labels');

        update_option('widget_isu_footer_contact', $widgetContact);
        update_option('widget_isu_footer_associates', $widgetAssociate);
        update_option('widget_isu_footer_legal', $widgetLegal);
        update_option('widget_isu_footer_social', $widgetSocial);

        $new_active_widgets = array(
            'sidebar-1' => array(),
            'footer-contact' => array('isu_footer_contact-2'),
            'footer-social' => array('isu_footer_social-2'),
            'footer-legal' => array('isu_footer_legal-2'),
            'footer-associates' => array('isu_footer_associates-2'),
        );
        update_option('sidebars_widgets', $new_active_widgets);
    }

}

add_action('after_switch_theme', 'isu_set_default_theme_widgets', 10, 2);

/**
 * Shortcode
 */
require get_template_directory() . '/inc/shortcode-carousel.php';

/**
 * Shortcode
 */
//require get_template_directory() . '/inc/admin.php';

/**
 * Widget
 */
#require get_template_directory() . '/inc/widget-carousel.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
//require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Bootstrap Menu Formatting
 */
require get_template_directory() . '/inc/theme_navwalker.php';

/**
 * Compatibility
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/extentions/jetpack.php';

require get_template_directory() . '/inc/updates.php';

require get_template_directory() . '/inc/mega-menus.php';

require get_template_directory() . '/inc/widgets.php';