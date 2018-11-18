<?php

use IastateTheme\Theme;

class WordPressISUTheme extends Theme
{
    public $loadSidebar = true;

    public function init()
    {
        $home = home_url();
        $title = get_bloginfo('name', 'display');
        $scripts = $this->getOption('inline_script');
        unset($scripts['file']['jquery']);
        $this->setOptions(array(
            'site_title' => $title,
            'site_url' => $home,
            'search_action' => $home,
            'request_uri' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
            'footer_associates' => array(
                array(
                    'label' => $title,
                    'url' => $home,
                )
            ),
            'theme_asset_path' => get_template_directory_uri() . '/vendor/iastate-theme/php/public',
        ));
        $this->setOption('inline_script', $scripts, true);
        /* Remove default theme favicons if WordPress has it set */
        if (has_site_icon()) {
            $headerLinks = $this->getOption('head_link');
            if (isset($headerLinks['favicon'])) {
                unset($headerLinks['favicon']);
            }
            if (isset($headerLinks['faviconpng'])) {
                unset($headerLinks['faviconpng']);
            }
            if (isset($headerLinks['faviconappletouch'])) {
                unset($headerLinks['faviconappletouch']);
            }
            $this->setOption('head_link', $headerLinks, true);
        }
    }

    public function renderHead()
    {
        if (has_nav_menu('header-right') && $this->getOption('show_site_links')) {
            wp_nav_menu(array(
                'menu' => 'header-right',
                'theme_location' => 'header-right',
                'depth' => 1,
                //'fallback_cb'     => 'theme_navwalker::fallback',
                'echo' => false,
                'walker' => new theme_navwalker($this, 'site_links')
            ));
        }
        if (has_nav_menu('primary') && $this->getOption('show_navbar_menu')) {
            wp_nav_menu(array(
                    'menu' => 'primary',
                    'theme_location' => 'primary',
                    'depth' => 4,
                    //'fallback_cb'     => 'theme_navwalker::fallback',
                    'echo' => false,
                    'walker' => new theme_navwalker($this, 'navbar_menu')
                )
            );
        }
        //$head = str_replace('</head>','',$this->filter( __FUNCTION__ ));
        return str_replace('</head>', '', $this->filter(__FUNCTION__));
    }

    private function dynamicSidebarCapture($sidebar)
    {
        $did_sidebar = false;
        $widget_output = '';
        if (is_active_sidebar($sidebar)) {
            ob_start();
            $did_sidebar = dynamic_sidebar($sidebar);
            $widget_output = ob_get_clean();
        }
        if ($did_sidebar) {
            return $widget_output;
        }

        return $did_sidebar;
    }

    public function renderBodyStart()
    {
        wp_head();

        return '</head>' . $this->filter(__FUNCTION__, 'replace', array(
                'body',
                'body class="' . join(' ', get_body_class()) . '"',
            ));
    }

    public function renderHtmlStart()
    {
        return $this->filter(__FUNCTION__, 'replace', array(
            'lang="en"',
            get_language_attributes(),
        ));
    }

    public function renderNavbarSiteSearch()
    {
        return $this->filter(__FUNCTION__, 'replace', array(
            'name="q"',
            'name="s"',
        ));
    }

    public function renderCarousel()
    {
        return $this->filter(__FUNCTION__, 'replace', array(
            'class="container"',
            '',
        ));
    }

    public function renderContentEnd()
    {
        return $this->filter(__FUNCTION__);
    }

    public function renderFooter()
    {
        return $this->filter(__FUNCTION__);
    }

    public function renderLoadingBar()
    {
        return $this->filter(__FUNCTION__);
    }

    public function renderInlineScript()
    {
        return $this->filter(__FUNCTION__);
    }

    public function renderBodyEnd()
    {
        wp_footer();

        return $this->filter(__FUNCTION__);
    }

    public function renderHtmlEnd()
    {
        return $this->filter(__FUNCTION__);
    }

    public function renderSkipNavigation()
    {
        return $this->filter(__FUNCTION__);
    }

    public function renderHeader()
    {
        return $this->filter(__FUNCTION__);
    }

    public function renderBreadcrumbs()
    {
        return $this->filter(__FUNCTION__);
    }

    public function renderPostNav()
    {
        return $this->filter(__FUNCTION__);
    }

    public function renderContentStart()
    {
        if ($this->loadSidebar) {
            $sidebar = $this->dynamicSidebarCapture('sidebar-1');
            if ($sidebar) {
                $this->setOption('right_sidebar', $sidebar);
            }
        }
        return $this->filter(__FUNCTION__);
    }

    /*
    public function renderPageTitle() {
        return $this->filter( __FUNCTION__, 'replace', array(
            'h1',
            'h1 class="entry-title"'
        ) );
    }
*/
    public function renderPageTitle()
    {
        do_action('isu_renderPageTitle_before');

        $classes = array('entry-title');

        if ($this->getOption('show_page_title') !== true) {
            $classes[] = 'screen-reader-text';
        }

        return '<h1 class="' . implode(' ', $classes) . '">' . $this->escape($this->getOption('page_title')) . '</h1>';
    }

    public function renderHeadTitle()
    {
        return wp_kses(parent::renderHeadTitle(), array(
            'title' => array()
        ));
    }

    public function renderFooterAssociates()
    {
        return $this->filter(__FUNCTION__, 'widgetHack', array('footer-associates'));
    }

    public function renderFooterContact()
    {
        return $this->filter(__FUNCTION__, 'widgetHack', array('footer-contact'));
    }

    public function renderFooterSocial()
    {
        return $this->filter(__FUNCTION__, 'widgetHack', array('footer-social'));
    }

    public function renderFooterLegal()
    {
        return $this->filter(__FUNCTION__, 'widgetHack', array('footer-legal'));
    }

    public function escape($html)
    {
        return wp_kses($html, array(
            'i' => array(
                'aria-hidden' => array(),
                'class' => array(),
            ),
            'span' => array(
                'aria-hidden' => array(),
                'class' => array(),
            ),
            'br' => array(),
        ));
    }

    private function filter($function, $childFunction = false, $args = null)
    {
        do_action('isu_' . $function . '_before');
        if ($childFunction) {
            $args [] = $function;

            return apply_filters('isu_' . $function, call_user_func_array(array($this, $childFunction), $args));
        }

        return apply_filters('isu_' . $function, parent::$function());
    }

    private function widgetHack($sidebar, $parentFunction)
    {
        $capture = $this->dynamicSidebarCapture($sidebar);
        if ($capture) {
            return "\n\n" . "<section class=\"{$sidebar} col-sm-12 col-md-3\">$capture</section>";
        } else {
            return "\n\n" . parent::$parentFunction();
        }
    }

    private function replace($find, $replace, $parentFunction)
    {
        /**
         * Upcoming fix to hide empty footer areas
         * @todo Uncomment when 'footer' plugins have had a chance to be used in the wild
         */
        //return "\n\n" . "<section class=\"{$sidebar} hidden-xs hidden-sm col-md-3 \"></section>";
        return str_replace($find, $replace, parent::$parentFunction());
    }

    public function widgetFooterAssociates()
    {
        return preg_replace('/<\/?section?[^>]+\>/i', "", parent::renderFooterAssociates());
    }

    public function widgetFooterContact()
    {
        return preg_replace('/<\/?section?[^>]+\>/i', "", parent::renderFooterContact());
    }

    public function widgetFooterSocial()
    {
        return preg_replace('/<\/?section?[^>]+\>/i', "", parent::renderFooterSocial());
    }

    public function widgetFooterLegal()
    {
        return preg_replace('/<\/?section?[^>]+\>/i', "", parent::renderFooterLegal());
    }

}
