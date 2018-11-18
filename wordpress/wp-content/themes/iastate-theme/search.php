<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Isu-theme
 */

iastate_theme(array(
        'page_title' => sprintf(esc_html__('Search Results for: %s', 'iastate-theme'), '<span>' . get_search_query() . '</span>')
    )
);

get_header();
if (have_posts()) : ?>
	<hr>
    <?php
    /* Start the Loop */
    while (have_posts()) : the_post();

        /**
         * Run the loop for the search to output the results.
         * If you want to overload this in a child theme then include a file
         * called content-search.php and that will be used instead.
         */
        get_template_part('template-parts/content', 'search');

    endwhile;

    echo isu_pagination();

else :

    get_template_part('template-parts/content', 'none');

endif;
get_footer();
