<?php

include get_template_directory() . '/inc/Widgets/FooterAssociates.php';
include get_template_directory() . '/inc/Widgets/FooterContact.php';
include get_template_directory() . '/inc/Widgets/FooterLegal.php';
include get_template_directory() . '/inc/Widgets/FooterSocial.php';


function register_foo_widget() {
    register_widget('FooterAssociates');
    register_widget('FooterContact');
    register_widget('FooterLegal');
    register_widget('FooterSocial');

}
add_action( 'widgets_init', 'register_foo_widget' );