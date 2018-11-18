<?php
/**
 * Isu-theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Isu-theme
 */



require get_template_directory() . '/vendor/autoload.php';
//require get_template_directory() . '/vendor/iastate-theme/php/src/Theme.php';

if (!function_exists('isu_theme_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function isu_theme_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Isu-theme, use a find and replace
         * to change 'iastate-theme' to the name of your theme in all the template files.
         */
        load_theme_textdomain('iastate-theme', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        //add_theme_support( 'title-tag' );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('Primary', 'iastate-theme'),
        ));

        register_nav_menus(array(
            'header-right' => esc_html__('Site Links', 'iastate-theme'),
        ));
        /*
                register_nav_menus( array(
                    'right-nav' => esc_html__( 'Right Navigation', 'iastate-theme' ),
                ) );
        */
        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        add_image_size('isu_carousel', 1260, 480);

    }
endif;

add_action('after_setup_theme', 'isu_theme_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function isu_theme_content_width()
{
    $GLOBALS['content_width'] = apply_filters('isu_theme_content_width', 1200);
}

add_action('after_setup_theme', 'isu_theme_content_width', 0);




/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function isu_theme_widgets_init()
{
    /** Sidebars */
    register_sidebar(array(
        'name' => esc_html__('Right Sidebar', 'iastate-theme'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'iastate-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    /** Footers */
    register_sidebar(array(
        'name' => esc_html__('Footer Associates', 'iastate-theme'),
        'id' => 'footer-associates',
        'description' => esc_html__('Add departmental relationship information.', 'iastate-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Contact Information', 'iastate-theme'),
        'id' => 'footer-contact',
        'description' => esc_html__('Add contact information.', 'iastate-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer External Links', 'iastate-theme'),
        'id' => 'footer-social',
        'description' => esc_html__('Add external links such as a list of social media.', 'iastate-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Legal', 'iastate-theme'),
        'id' => 'footer-legal',
        'description' => esc_html__('Legal information.', 'iastate-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    /** Homepage Area */
    register_sidebar(array(
        'name' => esc_html__('Homepage First Row', 'iastate-theme'),
        'id' => 'homepage-row-1',
        'description' => esc_html__('First row for the Homepage template.', 'iastate-theme'),
        'before_widget' => '<div id="%1$s" class="widget isu-feature col-md-' . isu_homepage_column_size('homepage-row-1') . ' %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="isu-feature-header">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Homepage Second Row', 'iastate-theme'),
        'id' => 'homepage-row-2',
        'description' => esc_html__('Second row for the Homepage template.', 'iastate-theme'),
        'before_widget' => '<div id="%1$s" class="widget isu-feature col-md-' . isu_homepage_column_size('homepage-row-2') . ' %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="isu-feature-header">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'isu_theme_widgets_init');

/**
 * @param string $sidebar_id
 * @return int|null
 */
function isu_widget_count($sidebar_id)
{
    $widgets = wp_get_sidebars_widgets();
    if (is_customize_preview()) {
        global $wp_customize;
        $options = $wp_customize->unsanitized_post_values();
        $option_name = sprintf('sidebars_widgets[%1$s]', $sidebar_id);
        if (isset($options[$option_name])) {
            return count($options[$option_name]);
        } elseif (isset($widgets[$sidebar_id])) {
            return count($widgets[$sidebar_id]);
        }
    } else {
        if (isset($widgets[$sidebar_id])) {
            return count($widgets[$sidebar_id]);
        }
    }
    return null;
}

/**
 * Gets number of widgets in sidebar to determine bootstrap column size
 *
 * @param string $sidebar_id
 * @return int
 */
function isu_homepage_column_size($sidebar_id)
{
    $columns = 12;
    $count = isu_widget_count($sidebar_id);
    if ($count && $count > 0) {
        $columns = round($columns / $count);
    }
    return $columns;
}


/**
 * Enqueue scripts and styles.
 */
function isu_theme_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_style('isu-theme-style', get_stylesheet_uri());

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'isu_theme_scripts');

add_filter('embed_oembed_html', 'isu_bootstrap_class_oembed');
function isu_bootstrap_class_oembed($code)
{
    $code = str_replace('class="', 'class="embed-responsive-item ', $code);

    return $code;
}

/**
 * Load the ISU Theme Class
 */
require get_template_directory() . '/inc/theme-config.php';

function iastate_theme_register_defaults($pageTitleOrOptions, $options)
{
    return new WordPressISUTheme($pageTitleOrOptions, $options);
}

function isu_post_meta_override()
{
    var_dump(get_post_meta(get_the_ID(), 'test'));
}

// add_action( $tag, $function_to_add, $priority, $accepted_args );
add_filter('iastate_register_defaults', 'iastate_theme_register_defaults', 10, 2);

function iastate_theme_do_header($options = array())
{
    /**
     * @var \WordPressISUTheme $WPISUTheme
     */
    global $WPISUTheme;

    $options = array_merge(isu_post_meta_theme_options(get_the_ID()), $options);

    $theme_mods = get_theme_mod('isu_options', array());

    if (is_front_page() && isset($theme_mods['homepage_carousel_imgs'])) {
        isu_carousel_shortcode(array('ids' => $theme_mods['homepage_carousel_imgs']));
    }
    if (isset($theme_mods['footer_associates'])) {
        if (empty($theme_mods['footer_associates'])) {
            unset($theme_mods['footer_associates']);
        } else {
            $cleanAssociates = array_filter($theme_mods['footer_associates']);
            if (empty($cleanAssociates)) {
                unset($theme_mods['footer_associates']);
            } else {
                isu_textrea_to_label($theme_mods['footer_associates']);
            }
        }
    }
    if (isset($theme_mods['footer_social'])) {
        if (empty($theme_mods['footer_social'])) {
            unset($theme_mods['footer_social']);
        } else {
            isu_textrea_to_url($theme_mods['footer_social']);
        }
    }
    if (isset($theme_mods['footer_contact'])) {
        if (empty($theme_mods['footer_contact'])) {
            unset($theme_mods['footer_contact']);
        } else {
            $defaultContacts = $WPISUTheme->getOption('footer_contact');
            $cleanContacts = array_filter($theme_mods['footer_contact']);
            if (empty($cleanContacts)) {
                unset($theme_mods['footer_contact']);
            } else {
                foreach ($theme_mods['footer_contact'] as $key => $value) {
                    if ($key === 'address' && is_string($value)) {
                        $theme_mods['footer_contact'][$key] = preg_split("/\\r\\n|\\r|\\n/", $value);
                    }
                    if ($key === 'address_url' || $value != '') {
                        $defaultContacts[$key] = $theme_mods['footer_contact'][$key];
                    } else {
                        $defaultContacts[$key] = array();
                    }
                }

                $theme_mods['footer_contact'] = $defaultContacts;
            }
        }
    }
    $WPISUTheme->setOptions($theme_mods);

    if (!empty($options)) {
        $WPISUTheme->setOptions($options);
    }
    $WPISUTheme->drawHeader();
}

function isu_post_meta_theme_options($id)
{
    $meta = get_post_meta($id);
    $toCheck = array('post_nav', 'navs');
    $options = array();
    foreach ($toCheck as $check) {
        if (isset($meta[$check])) {
            $options[$check] = do_shortcode(current($meta[$check]));
        }
    }
    return $options;
}

function isu_pagination($args = array()){
    $pagination = '';

    $options = array_merge($args,
        array(
            'prev_text' => '<span class="fa fa-backward" aria-label="previous"></span></span><span class="sr-only"> previous</span>',
            'next_text' => '<span class="fa fa-forward" aria-label="next"></span></span><span class="sr-only"> next</span>',
        ));
    $options['type'] = 'array';

    $links = paginate_links($options);
    if ($links) {
        foreach ($links as $link) {
            $class = '';
            if (false !== strpos($link, 'current')) {
                $class = ' class="active"';
            }
            if (false !== strpos($link, 'dots')) {
                $class = ' class="disabled"';
            }
            $pagination .= "<li{$class}>{$link}</li>";
        }
        return <<<HTML
<nav aria-label="Page navigation">
\t<ul class="navigation pagination">
\t\t{$pagination}
\t</ul>
</nav>
HTML;
    }
    return '';
}

function isu_posts_navigation($args = array())
{
    $navigation = '';

    // Don't print empty markup if there's only one page.
    if ($GLOBALS['wp_query']->max_num_pages > 1) {
        $args = wp_parse_args($args, array(
            'prev_text' => __('Older posts'),
            'next_text' => __('Newer posts'),
            'screen_reader_text' => __('Posts navigation'),
        ));

        $next_link = get_previous_posts_link($args['next_text']);
        $prev_link = get_next_posts_link($args['prev_text']);

        if ($prev_link) {
            $navigation .= '<li class="previous">' . $prev_link . '</li>';
        }

        if ($next_link) {
            $navigation .= '<li class="next">' . $next_link . '</li>';
        }

        $navigation = _navigation_markup($navigation, 'posts-navigation', $args['screen_reader_text']);
    }

    return $navigation;
}


function isu_nav_template($template)
{
    return '
    <nav class="navigation %1$s" role="navigation" aria-label="%2$s">
		<ul class="nav-links pager">%3$s</ul>
	</nav>';
}

add_filter('navigation_markup_template', 'isu_nav_template');

function isu_textrea_to_label(&$input)
{
    foreach ($input as $key => $e) {
        if (!isset($e['url'])) {
            $tmp = explode(':', $e, 2);
            $transformed = array('label' => null, 'url' => null);
            if (isset($tmp[0])) {
                $transformed['label'] = $tmp[0];
            }
            if (isset($tmp[1])) {
                $transformed['url'] = $tmp[1];
            }
            $input[$key] = $transformed;
        }
    }
}

function isu_textrea_to_url(&$input)
{
    foreach ($input as $key => $e) {
        if (!isset($e['url'])) {
            $tmp = explode(':', $e, 2);
            $transformed = array('label' => null, 'url' => null);
            if (isset($tmp[1])) {
                $transformed['label'] = $tmp[0];
                $transformed['url'] = $tmp[1];
            } else {
                $transformed['url'] = $tmp[0];
            }

            $input[$key] = $transformed;
        }
    }
}

add_action('iastate_theme_header', 'iastate_theme_do_header', 11);

function iastate_theme_do_footer($options = null)
{
    /**
     * @var \WordPressISUTheme $WPISUTheme
     */
    global $WPISUTheme;

    if (is_array($options)) {
        $WPISUTheme->setOptions($options);
    }
    $WPISUTheme->drawFooter();
}

add_action('iastate_theme_footer', 'iastate_theme_do_footer', 11);


function iastate_load_theme($pageTitleOrOptions = null, $options = array())
{
    global $WPISUTheme;
    $WPISUTheme = apply_filters('iastate_register_defaults', $pageTitleOrOptions, $options);
}

add_action('template_redirect', 'iastate_load_theme', 10, 2);

function iastate_theme($options = array())
{
    /**
     * @var \WordPressISUTheme $WPISUTheme
     */
    global $WPISUTheme;
    if (is_array($options) && !empty($options)) {
        $WPISUTheme->setOptions($options);
    }

    return $WPISUTheme;
}

/**
 * Load Theme Files
 */
require get_template_directory() . '/inc/main.php';
$WPISUTheme = new WordPressISUTheme();
