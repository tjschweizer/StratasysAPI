<?php
/**
 * Isu-theme Theme Customizer.
 *
 * @package Isu-theme
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function isu_theme_customize_register( $wp_customize ) {
	$WPISUTheme                                                = new WordPressISUTheme();
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	//$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	//$wp_customize->remove_control( 'blogdescription' );
	$wp_customize->add_panel( 'isu_theme', array(
		'title'       => __( 'ISU Theme' ),
		'description' => __( 'Description of panel', 'iastate-theme' ), // Include html tags such as <p>.
		'priority'    => 105, // Mixed with top-level-section hierarchy.
	) );

	$wp_customize->add_section( 'isu_theme_options_head', array(
		'title'       => __( 'Head Options', 'iastate-theme' ),
		'description' => __( 'Description of section', 'iastate-theme' ),
		'panel'       => 'isu_theme',
	) );
	$wp_customize->add_section( 'isu_theme_options_body', array(
		'title'       => __( 'Body Options', 'iastate-theme' ),
		'description' => __( 'Description of section', 'iastate-theme' ),
		'panel'       => 'isu_theme',
	) );
	$wp_customize->add_section( 'isu_theme_options_footer', array(
		'title'       => __( 'Footer Options', 'iastate-theme' ),
		'description' => __( 'Description of section', 'iastate-theme' ),
		'panel'       => 'isu_theme',
	) );

	$options   = array(
		'head'   => array(
			'show_header'         => 'checkbox',
			'show_navbar_iastate' => 'checkbox',
			'show_navbar_site'    => 'checkbox',
			'show_search_box'     => 'checkbox',
			'show_site_links'     => 'checkbox',
			'show_navbar_menu'    => 'checkbox',
			'navbar_menu_affix'   => 'checkbox',
			'navbar_menu_hover'   => 'checkbox',
			'show_site_title'     => 'checkbox',
			'site_title'          => 'text',
			'search_action'       => 'text',
			'search_placeholder'  => 'text',
			'title_separator'     => 'text',
		),
		'body'   => array(
			'show_page_title'      => 'checkbox',
			'show_breadcrumbs'     => 'checkbox',
			'full_width'           => 'checkbox',
			'right_nav_affix'      => 'checkbox',
			'right_nav_scroll_spy' => 'checkbox',
			'right_nav_collapse'   => 'checkbox',
		),
		'footer' => array(
			'show_footer'        => 'checkbox',
			'show_social_labels' => 'checkbox',
			//'footer_associates'  => 'textarea',
			//'footer_social'      => 'textarea',
		),
	);
	$callbacks = array(
		'checkbox' => 'isu_top_nav_callback',
		'text'     => 'isu_string_option_callback',
		'textarea' => 'isu_callback_comma_delimit'
	);
	foreach ( $options as $name => $section ) {
		foreach ( $section as $option => $type ) {
			$wp_customize->add_setting( "isu_options[{$option}]", array(
				'type'              => 'theme_mod', // or 'option'
				'capability'        => 'edit_theme_options',
				'theme_supports'    => '', // Rarely needed.
				'default'           => $WPISUTheme->getOption( $option, '' ),
				'transport'         => 'refresh', // or postMessage
				'sanitize_callback' => $callbacks[ $type ],
				//'sanitize_js_callback' => '', // Basically to_json.
			) );
			$wp_customize->add_control( new WP_Customize_Control(
				$wp_customize,
				"isu_options[{$option}]",
				array(
					//'label'          => __( 'Dark or light theme version?', 'theme_name' ),
					'section'     => 'isu_theme_options_' . $name,
					'settings'    => "isu_options[{$option}]",
					'type'        => $type,
					'label'       => isu_customizer_labels( $option ),
					'description' => isu_customizer_descriptions( $option ),
				)
			) );
		}
	}

	$default_footer_associates = $WPISUTheme->getOption( 'footer_associates' );
	foreach ($default_footer_associates as $key=>$value){
		if(is_array($value)){
			$default_footer_associates[$key] = $value['label'].':'.$value['url'];
		}
	}
	$wp_customize->add_setting( "isu_options[footer_associates]", array(
		'type'              => 'theme_mod', // or 'option'
		'capability'        => 'edit_theme_options',
		'theme_supports'    => '', // Rarely needed.
		'default'           => $default_footer_associates,
		'transport'         => 'refresh', // or postMessage
		'sanitize_callback' => $callbacks['textarea'],
		//'sanitize_js_callback' => '', // Basically to_json.
	) );
	$wp_customize->add_control( new WP_Customize_Control(
		$wp_customize,
		"isu_options[footer_associates]",
		array(
			//'label'          => __( 'Dark or light theme version?', 'theme_name' ),
			'section'     => 'isu_theme_options_footer',
			'settings'    => "isu_options[footer_associates]",
			'type'        => 'textarea',
			'label'       => isu_customizer_labels( 'footer_associates' ),
			'description' => isu_customizer_descriptions( 'footer_associates' ),
		)
	) );
	$default_footer_social = $WPISUTheme->getOption( 'footer_social' );
	foreach ($default_footer_social as $key=>$value){
		if(is_array($value)){
			$default_footer_social[$key] = $value['label'].':'.$value['url'];
		}
	}
	$wp_customize->add_setting( "isu_options[footer_social]", array(
		'type'              => 'theme_mod', // or 'option'
		'capability'        => 'edit_theme_options',
		'theme_supports'    => '', // Rarely needed.
		'default'           => $default_footer_social,
		'transport'         => 'refresh', // or postMessage
		'sanitize_callback' => $callbacks[ 'textarea' ],
		//'sanitize_js_callback' => '', // Basically to_json.
	) );
	$wp_customize->add_control( new WP_Customize_Control(
		$wp_customize,
		"isu_options[footer_social]",
		array(
			//'label'          => __( 'Dark or light theme version?', 'theme_name' ),
			'section'     => 'isu_theme_options_footer',
			'settings'    => "isu_options[footer_social]",
			'type'        => 'textarea',
			'label'       => isu_customizer_labels( 'footer_social' ),
			'description' => isu_customizer_descriptions( 'footer_social' ),
		)
	) );

	$default_footer_contact = $WPISUTheme->getOption( 'footer_contact' );
	foreach ($default_footer_contact as $key=>$value){
		if (is_array($value)){
			$default_footer_contact[$key] = implode("\n",$value);
		}
	}
	//var_dump($default_footer_contact);
	$footer_options = array(
		'address' => array( 'type' => 'textarea', 'callback' => 'isu_string_option_callback' ),
		'address_url' => array( 'type' => 'text', 'callback' => 'isu_string_option_callback' ),
		'email'   => array( 'type' => 'email', 'callback' => 'isu_callback_comma_delimit' ),
		'phone'   => array( 'type' => 'text', 'callback' => 'isu_callback_comma_delimit' ),
		'fax'     => array( 'type' => 'text', 'callback' => 'isu_callback_comma_delimit' ),
	);
	foreach ( $footer_options as $option => $o ) {
		$wp_customize->add_setting( "isu_options[footer_contact][{$option}]", array(
			'type'              => 'theme_mod', // or 'option'
			'capability'        => 'edit_theme_options',
			'default'           => isset( $default_footer_contact[ $option ] ) && $default_footer_contact[ $option ] != null ? $default_footer_contact[ $option ]: '',
			'theme_supports'    => '', // Rarely needed.
			'transport'         => 'refresh', // or postMessage
			'sanitize_callback' => $o['callback'],
			//'sanitize_js_callback' => '', // Basically to_json.
		) );
		$wp_customize->add_control( new WP_Customize_Control(
			$wp_customize,
			"isu_options[footer_contact][{$option}]",
			array(
				//'label'          => __( 'Dark or light theme version?', 'theme_name' ),
				'section'     => 'isu_theme_options_footer',
				'settings'    => "isu_options[footer_contact][{$option}]",
				'label'       => isu_customizer_labels( "footer_contact_{$option}" ),
				'description' => isu_customizer_descriptions( "footer_contact_{$option}" ),
				'type'        => $o['type']
			)
		) );
	}




	/*
	$wp_customize->add_control( new WP_Customize_Cropped_Image_Control(
		$wp_customize,
		'homepage_carousel_images',
		array(
			//'label'          => __( 'Dark or light theme version?', 'theme_name' ),
			'section'        => 'isu_theme_options',
			'settings'       => 'homepage_carousel_images',
			'label'    => isu_customizer_labels('homepage_carousel_images'),
			'mime_type' => 'image',
			'flex_width'  => true, // Allow any width, making the specified value recommended. False by default.
			'flex_height' => false, // Require the resulting image to be exactly as tall as the height attribute (default).
			'width'       => 1920,
			'height'      => 1080,
			//'description' => isu_customizer_descriptions('homepage_carousel_imgs'),
		)
	));
*/

}

add_action( 'customize_register', 'isu_theme_customize_register' );

function isu_customizer_descriptions( $value ) {
	$descriptions = array(
		'default'                => esc_html__( 'No Description', 'iastate-theme' ),
		'title_separator'        => esc_html__( 'The title separator used to separate each title part in Head Title', 'iastate-theme' ),
		'show_header'            => esc_html__( 'Whether to show the template header containg the dark top strip, red ribbon, and navbar.', 'iastate-theme' ),
		'show_navbar_iastate'    => esc_html__( 'Whether to show the dark top strip containing utility links like site-wide logins, ISU index, directory, maps, etc.', 'iastate-theme' ),
		'show_navbar_site'       => esc_html__( 'Whether to show the red ribbon containing the search bar, site nameplate, and site title and tagline.', 'iastate-theme' ),
		'show_search_box'        => esc_html__( 'Whether to show the search box.', 'iastate-theme' ),
		'show_site_links'        => esc_html__( 'Whether to show the site links beneath the search bar.', 'iastate-theme' ),
		'show_site_title'        => esc_html__( 'Whether to show the site title in the red ribbon.', 'iastate-theme' ),
		'show_page_title'        => esc_html__( 'Whether to render a page title contained within an <h1> before the page content.', 'iastate-theme' ),
		'show_navbar_menu'       => esc_html__( 'Whether to show the dropdown navigation.', 'iastate-theme' ),
		'navbar_menu_hover'      => esc_html__( 'Whether to display dropdown on hover(true) or click(false).', 'iastate-theme' ),
		'navbar_menu_affix'      => esc_html__( 'Whether to affix navbar_menu to top of screen on scroll.', 'iastate-theme' ),
		'right_nav_affix'        => esc_html__( 'Whether to affix the right_nav to the top of the screen on scroll.', 'iastate-theme' ),
		'right_nav_scroll_spy'   => esc_html__( 'Whether to add active link styling to the currently scrolled to target on the page in the right_nav. This config by default sets right_nav_affix to true.', 'iastate-theme' ),
		'right_nav_collapse'     => esc_html__( 'Whether to collapse the sub_targets lists until the parent target is active (via scroll spy).', 'iastate-theme' ),
		'show_breadcrumbs'       => esc_html__( 'Whether to show the route trail as linked breadcrumbs at the top of the page below the navbar menu.', 'iastate-theme' ),
		'show_footer'            => esc_html__( 'Whether to show the footer.', 'iastate-theme' ),
		'show_social_labels'     => esc_html__( 'Whether to show the social link labels next to the icons.', 'iastate-theme' ),
		'full_width'             => esc_html__( 'Whether to make the site stretch the full width', 'iastate-theme' ),
		'navbar_caps'            => esc_html__( 'Uppercase menu labels', 'iastate-theme' ),
		'site_title'             => esc_html__( 'The site title displayed in the left hand side of the red ribbon.', 'iastate-theme' ),
		'search_action'          => esc_html__( 'The action used to submit the search form.', 'iastate-theme' ),
		'search_placeholder'     => esc_html__( 'The search input placeholder text.', 'iastate-theme' ),
		'footer_contact_address' => esc_html__( 'Contact address', 'iastate-theme' ),
		'footer_contact_address_url' => esc_html__( 'Link assigned to address', 'iastate-theme' ),
		'footer_contact_email'   => esc_html__( 'Contact email', 'iastate-theme' ),
		'footer_contact_phone'   => esc_html__( 'Contact phone number', 'iastate-theme' ),
		'footer_contact_fax'     => esc_html__( 'Contact fax', 'iastate-theme' ),
		'footer_social'          => __( 'Social links. <br>Comma delimited (,)<br>format: <strong>"[url]:[label(optional)]"</strong>', 'iastate-theme' ),
		'footer_associates'      => __( 'Associated departmets. <br>Comma delimited (,)<br>format: <strong>"[label]:[url(optional)]"</strong>', 'iastate-theme' ),
	);

	if ( isset( $descriptions[ $value ] ) ) {
		return $descriptions[ $value ];
	}

	return null;
}

function isu_customizer_labels( $value ) {
	$labels = array();

	if ( isset( $labels[ $value ] ) ) {
		return $labels[ $value ];
	}

	return ucwords( str_replace( '_', ' ', $value ) );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function isu_theme_customize_preview_js() {
	wp_enqueue_script( 'isu_theme_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}

function isu_links_callback( $input ) {
	if ( $input === '' ) {
		return array();
	}

	return preg_split( "/\\r\\n|\\r|\\n/", $input );
}

function isu_callback_comma_delimit( $input = '' ) {
	if ( $input === '' ) {
		return '';
	}
	if ( empty( $input ) ) {
		return '';
	}

	return explode( ',', $input );
}

function isu_callback_url_with_label( $input ) {
	if ( $input === '' ) {
		return array();
	}
	$elements = isu_links_callback( $input );
	foreach ( $elements as $key => $e ) {
		$tmp = explode( ':', $e, 2 );
		if ( isset( $tmp[0] ) ) {
			$elements[ $key ]['label'] = $tmp[0];
		}
		if ( isset( $tmp[1] ) ) {
			$elements[ $key ]['url'] = $tmp[1];
		}
	}

	return $elements;
}

function isu_top_nav_callback( $input ) {
	return (bool) $input;
}

function isu_string_option_callback( $input = '' ) {
	return $input;
}

add_action( 'customize_preview_init', 'isu_theme_customize_preview_js' );
