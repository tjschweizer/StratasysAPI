<?php
if ('posts' == get_option('show_on_front')) :
    include(get_home_template());
else :
    global $WPISUTheme;
    $WPISUTheme->loadSidebar = false;
    get_header('title');
    ?>
	<article id="homepage" class="col-xs-12" <?php post_class(); ?>>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="entry-content">
                <?php the_content(); ?>
			</div><!-- .entry-content -->
        <?php endwhile; endif; ?>
	</article><!-- #post-## -->

    <?php if (is_active_sidebar('homepage-row-1')) { ?>
	<div id="homepage-features-one" class="isu-features widget-area row" role="complementary">
        <?php dynamic_sidebar('homepage-row-1'); ?>
	</div><!-- #primary-sidebar -->
<?php } ?>
    <?php if (is_active_sidebar('homepage-row-2')) { ?>
	<div id="homepage-features-two" class="isu-features widget-area row" role="complementary">
        <?php dynamic_sidebar('homepage-row-2'); ?>
	</div><!-- #primary-sidebar -->
    <?php
}
    get_footer();
endif;

