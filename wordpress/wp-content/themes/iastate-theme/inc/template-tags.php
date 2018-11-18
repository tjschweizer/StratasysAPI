<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Isu-theme
 */

if ( ! function_exists( 'isu_theme_posted_on' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function isu_theme_posted_on() {
        $write_comments = '';
        $edit_link      = '';
        $time_string = <<<'HTML'
<span data-toggle="tooltip" data-placement="bottom" title="Posted On %1$s" class="posted-on">
<time class="entry-date published post-info" datetime="%2$s">%3$s</time>
</span>
HTML;

        $time_string = sprintf($time_string,
            esc_attr(get_the_date('F j, Y') . ' ' . get_the_time()),
            esc_attr(get_the_date('c')),
            esc_html(get_the_date())
        );

        $byline = ' • <span class="author vcard">'
            . '<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';

        $num_comments = get_comments_number(); // get_comments_number returns only a numeric value

        if ( get_comments_number() > 0 ) {
            if ( $num_comments == 0 ) {
                $comments = __( 'No Comments', 'iastate-theme' );
            } elseif ( $num_comments > 1 ) {
                $comments = $num_comments . __( ' Comments', 'iastate-theme' );
            } else {
                $comments = __( '1 Comment', 'iastate-theme' );
            }
            $write_comments = ' <span class="comment-count"> <span class="fa fa-comments-o" aria-hidden="true"></span> <a href="' . get_comments_link() . '" aria-labelledby="post-header-'.get_the_ID().'">' . $comments . '</a></span>';
        }

        if ( current_user_can( 'edit_post', get_the_ID() ) ) {
            $edit_link = '<span class="edit-link"> <span class="fa fa-pencil" aria-hidden="true"></span> <a href="' . get_edit_post_link() . '">Edit Post</a></span>';
        }
        echo  $time_string . '<span class="byline"> ' . $byline . '</span>' . $write_comments . $edit_link .
            '';// WPCS: XSS OK.

    }
endif;

if ( ! function_exists( 'isu_theme_entry_footer' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function isu_theme_entry_footer() {
        // Hide category and tag text for pages.
        if ( 'post' === get_post_type() ) {
            $categories_string = '';
            $tags_string       = '';
            $updated_string    = '';

            /*
            if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
                $updated_string = ' <small data-toggle="tooltip" data-placement="bottom" title="Last Updated"><span class="fa fa-clock-o" aria-hidden="true"></span> <time class="updated" datetime="%1$s">%2$s</time></small>';
                $updated_string = sprintf( $updated_string,
                    esc_attr( get_the_modified_date( 'c' ) ),
                    esc_html( get_the_modified_date() )
                );
            }
            */

            /* translators: used between list items, there is a space after the comma */
            //$categories_list = get_the_category_list( esc_html__( ', ', 'iastate-theme' ) );
            $categories_list = isu_theme_get_the_taxonomies('category');

            if ( $categories_list && isu_theme_categorized_blog() ) {
                $categories_string = sprintf( '<span class="cat-links"> <span class="fa fa-folder-open" aria-hidden="true"></span> ' . esc_html__( ' %1$s', 'iastate-theme' ) . '</span>', $categories_list ); // WPCS: XSS OK.
            }

            //isu_theme_get_the_tags();
            /* translators: used between list items, there is a space after the comma */
            //$tags_list = get_the_tag_list( '', esc_html__( ', ', 'iastate-theme' ) );
            $tags_list = isu_theme_get_the_taxonomies('post_tag');
            if ( $tags_list ) {
                $tags_string = sprintf( ' <span class="tags-links"> <span class="fa fa-tag" aria-hidden="true"></span> ' . esc_html__( ' %1$s', 'iastate-theme' ) . '</span>', $tags_list ); // WPCS: XSS OK.
            }

            echo '<div class="">' . $categories_string . $tags_string . $updated_string.'</div>';

        }
        /*
                if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
                    echo '<span class="comments-link">';
                    // translators: %s: post title //
                    comments_popup_link(
                        sprintf(
                            wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'iastate-theme' ), array( 'span' => array( 'class' => array() ) ) ),
                            get_the_title()
                        )
                    );
                    echo '</span>';
                }
                */

        /*
        edit_post_link(
            sprintf(
            // translators: %s: Name of current post
                esc_html__( 'Edit %s', 'iastate-theme' ),
                the_title( '<span class="screen-reader-text">"', '"</span>', false )
            ),
            '<div class="badge pull-right">',
            '</div>'
        );
*/
    }
endif;
if ( ! function_exists( 'isu_theme_get_the_terms' ) ) :
    function isu_theme_get_the_taxonomies($taxonomy){
        $terms = get_the_terms(0,$taxonomy);
        if ( is_wp_error( $terms ) )
            return $terms;

        if ( empty( $terms ) )
            return false;

        $links = array();

        foreach ( $terms as $term ) {
            $link = get_term_link( $term, $taxonomy);
            if ( is_wp_error( $link ) ) {
                return $link;
            }
            $links[] = '<a href="' . esc_url( $link ) . '" rel="tag" class="label label-default">' . $term->name . '</a>';
        }

        return join( ' ', $links ) ;

    }
endif;

function isu_bootstrap_post_tags( $links ) {

	foreach ( $links as $id => $link ) {
		$links[ $id ] = '<a class="btn btn-default btn-sm"' . substr( $link, 2 );
	}

	return $links;
}

//add_filter( 'term_links-post_tag', 'isu_bootstrap_post_tags' );

function isu_bootstrap_categories( $thelist, $separator, $parents ) {

	if ( is_admin() ) {
		return $thelist;
	}
	$links = array();
	foreach ( explode( $separator, $thelist ) as $id => $link ) {
		$links[ $id ] = '<a class="btn btn-default btn-sm"' . substr( $link, 2 );
	}

	return implode( '', $links );
}

//add_filter( 'the_category', 'isu_bootstrap_categories', 10, 3 );

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function isu_theme_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'isu_theme_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'isu_theme_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so isu_theme_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so isu_theme_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in isu_theme_categorized_blog.
 */
function isu_theme_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'isu_theme_categories' );
}

add_action( 'edit_category', 'isu_theme_category_transient_flusher' );
add_action( 'save_post', 'isu_theme_category_transient_flusher' );

function isu_theme_action_button() {
	if ( is_user_logged_in() ) {
		echo '<!-- Single button -->
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Actions
            </button>
            <ul class="dropdown-menu">
                <li><a href="' . get_edit_post_link() . '"><span class="fa fa-pencil" aria-hidden="true"></span> Edit</a></li>
            </ul>
        </div>';
	}
}
