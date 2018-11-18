<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Isu-theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> aria-labelledby="post-header-<?php the_ID(); ?>">
    <?php if (is_single()) : ?>
		<div class="entry-meta">
            <?php
            if ('post' === get_post_type()) : ?>
				<div class="entry-">
                    <?php isu_theme_posted_on(); ?>
				</div><!-- .entry-meta -->
            <?php endif; ?>
            <?php isu_theme_entry_footer(); ?>
		</div>
		<div class="entry-content">
            <?php
            the_content(sprintf(
            /* translators: %s: Name of current post. */
                wp_kses(__('Continue reading %s <span class="meta-nav">&rarr;</span>', 'iastate-theme'), array('span' => array('class' => array()))),
                the_title('<span class="screen-reader-text">"', '"</span>', false)
            ));

            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'iastate-theme'),
                'after' => '</div>',
            ));
            ?>
		</div><!-- .entry-content -->
    <?php else :
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
				<h2 id="post-header-<?php the_ID(); ?>" class="media-heading entry-header">
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
    <?php endif; ?>

</article><!-- #post-## -->
