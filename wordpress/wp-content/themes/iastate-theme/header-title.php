<?php
/**
 * The header for our theme with title.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Isu-theme
 */

$options = array();

if (is_home() && !is_front_page()) {
    $post_id = get_option( 'page_for_posts' );
    $options['page_title'] = single_post_title('', false);
} else {
    $post_id = get_the_ID();
    $options['page_title'] = get_the_title();
}

$title_toggle = get_post_meta($post_id, 'title_visibility', true);

if ($title_toggle === 'hide') {
    $options['show_page_title'] = false;
}

do_action('iastate_theme_header', $options);