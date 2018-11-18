<?php

// [isu-carousel imgs="1,2,3" container-class="post-nav"]
function isu_carousel_shortcode( $attr ) {
	global $WPISUTheme;
	$post = get_post();

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	$atts = shortcode_atts( array(
		'order'    => 'ASC',
		'orderby'  => 'menu_order ID',
		'id'       => $post ? $post->ID : 0,
		'size'     => 'carousel',
		'include'  => '',
		'exclude'  => '',
		'link'     => 'link',
		'captions' => true,
		'side_nav' => false,
	), $attr, 'carousel' );

	$id = intval( $atts['id'] );

	if ( preg_match( '/^[0-9]+,[0-9]+/', $atts['size'] ) ) {
		$sizeArray    = explode( ',', $atts['size'] );
		$atts['size'] = array();
		foreach ( $sizeArray as $s ) {
			$atts['size'][] = intval( $s );
		}
	}

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array(
			'include'        => $atts['include'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby']
		) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[ $val->ID ] = $_attachments[ $key ];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children(
			array(
				'post_parent'    => $id,
				'exclude'        => $atts['exclude'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby']
			) );
	} else {
		$attachments = get_children(
			array(
				'post_parent'    => $id,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby']
			) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}

		return $output;
	}

	$content = array();
	foreach ( $attachments as $id => $attachment ) {
		$meta = get_post_meta( $id );
		$content[] = array(
			'src'         => wp_get_attachment_image_url( $id, $atts['size'] ),
			'url'         => isset($meta['meta_link']) ? current($meta['meta_link']):'',
			'title'       => wptexturize( $attachment->post_excerpt ),
			'description' => wptexturize( $attachment->post_content ),
			'alt'         => isset($meta['_wp_attachment_image_alt']) ? current($meta['_wp_attachment_image_alt']):'',
		);
	}
	//$carouselBack = $WPISUTheme->getOption( 'carousel' );
	$carousel = array(
		'content'           => $content,
		'show_side_buttons' => $atts['side_nav'],
		'show_captions'     => $atts['captions'],
	);
	$WPISUTheme->setOptions(array(
		'show_carousel'     => true,
		'carousel' => $carousel,
	));

	return $WPISUTheme->renderCarousel();
	//echo $output;
	//$WPISUTheme->setOption( 'carousel', $carouselBack );

	//return true;

}

add_shortcode( 'isu_carousel', 'isu_carousel_shortcode' );

/**
 * Add custom field
 */
function mytheme_attachment_fields( $form_fields, $post ) {
	if ( substr( $post->post_mime_type, 0, 5 ) == 'image' ) {
		$meta = get_post_meta( $post->ID, 'meta_link', true );

		$form_fields['meta_link'] = array(
			'label' => __( 'Carousel URL', 'iastate-theme' ),
			//'helps'        => __( 'Link for Carousel', 'iastate-theme' ),
			'input' => 'text',
			'value' => $meta,
		);

		$form_fields['image_alt']['required'] = true;
	}

	return $form_fields;
}

add_filter( 'attachment_fields_to_edit', 'mytheme_attachment_fields', 10, 2 );
/**
 * Update custom field on save
 */
function mytheme_update_attachment_meta( $post, $attachment ) {
	if ( isset( $attachment['meta_link'] ) ) {
		update_post_meta( $post['ID'], 'meta_link', esc_url( $attachment['meta_link'] ) );
	}

	return $post;
}

add_filter( 'attachment_fields_to_save', 'mytheme_update_attachment_meta', 4, 2 );
/**
 * Update custom field via ajax
 */
function mytheme_media_xtra_fields() {
	$post_id = $_POST['id'];
	$meta    = $_POST['attachments'][ $post_id ]['meta_link'];
	update_post_meta( $post_id, 'meta_link', esc_url( $meta ) );
	clean_post_cache( $post_id );
}

add_action( 'wp_ajax_save-attachment-compat', 'mytheme_media_xtra_fields', 0, 1 );

