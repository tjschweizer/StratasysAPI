<?php
/**
 * Created by PhpStorm.
 * User: kwickham
 * Date: 10/4/16
 * Time: 10:30 AM
 */

/*
 * Template Name: No Sidebar
 *
 */
global $WPISUTheme;
$WPISUTheme->loadSidebar = false;

get_header( 'title' );

while ( have_posts() ) : the_post();

	get_template_part( 'template-parts/content', 'page' );

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

endwhile; // End of the loop.

get_footer();
