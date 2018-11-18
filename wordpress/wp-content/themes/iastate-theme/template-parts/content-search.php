<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Isu-theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    $emptyTitle = false;
    if (get_the_title() === '') {
        $emptyTitle = true;
    }

    ?>
	<div class="media">
		<div class="media-left media-middle">
            <?php
            the_post_thumbnail('thumbnail', array('class' => 'media-object'));
            ?>
		</div>
		<div class="media-body">
			<h2 class="media-heading entry-header">
                <?php
                the_title('<a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a>');
                ?>
			</h2><!-- .entry-header -->
            <?php
            if ('post' === get_post_type()) :
                if ($emptyTitle) {
                    echo '<a style="text-decoration: underline;" href="' . esc_url(get_permalink()) . '" rel="bookmark">';
                }
                ?>
				<div class="entry-">
                    <?php isu_theme_posted_on(); ?>
				</div><!-- .entry-meta -->
                <?php
                if ($emptyTitle) {
                    echo '</a>';
                }
            endif; ?>
			<small><?php isu_theme_entry_footer(); ?></small>
			<div class="entry-content">
				<p>
                    <?php
                    echo get_the_excerpt();
                    ?>
				</p>
                <?php
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'iastate-theme'),
                    'after' => '</div>',
                ));
                ?>
			</div><!-- .entry-content -->
		</div>
	</div>
</article><!-- #post-## -->
