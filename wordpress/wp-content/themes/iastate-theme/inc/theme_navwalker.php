<?php

/**
 * Class theme_navwalker
 * @todo actually use the walker class instead of adding extra functions
 */
class theme_navwalker extends Walker_Nav_Menu {

	/**
	 * @var string $theme_name
	 */
	private $theme_name;
	/**
	 * @var \IastateTheme\Theme
	 */
	private $theme;

	/**
	 * theme_navwalker constructor.
	 *
	 * @param \IastateTheme\Theme $theme
	 */
	function __construct( &$theme, $theme_name = 'navbar' ) {
		$this->theme_name = $theme_name;
		$this->theme      = $theme;
	}

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id ) {
					$fb_output .= ' id="' . $container_id . '"';
				}

				if ( $container_class ) {
					$fb_output .= ' class="' . $container_class . '"';
				}

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id ) {
				$fb_output .= ' id="' . $menu_id . '"';
			}

			if ( $menu_class ) {
				$fb_output .= ' class="' . $menu_class . '"';
			}

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';

			if ( $container ) {
				$fb_output .= '</' . $container . '>';
			}

			echo $fb_output;
		}
	}

	function walk( $elements, $max_depth ) {
		$theme_array = $this->menu_transformer( $elements );
		$this->theme->setOptions( array( $this->theme_name => $theme_array ) );
	}

	static function menu_transformer( $menu_items ) {
		$parent_id = null;
		foreach ( $menu_items as $menu_item ) {
			$link         = $menu_item->url;
			$theme_menu[$menu_item->ID] = array(
				'label'     => $menu_item->title,
				'uri'       => esc_attr( $link ),
				'parent_id' => $menu_item->menu_item_parent,
				'class'   => implode(' ',array_filter($menu_item->classes))." menu-item-{$menu_item->ID}",
				'id'        => 'menu-item-'.$menu_item->ID,
			);
			if ($menu_item->description != '' ){
				$theme_menu[$menu_item->ID]['icon'] = htmlspecialchars($menu_item->description);
			}
			if ($menu_item->attr_title != '' ){
				$theme_menu[$menu_item->ID]['title'] = $menu_item->attr_title;
			}
			if ($menu_item->xfn != '' ){
				$theme_menu[$menu_item->ID]['rel'] = $menu_item->xfn;
			}
			if ($menu_item->target != '' ){
				$theme_menu[$menu_item->ID]['target'] = $menu_item->target;
			}
			if ($menu_item->object == 'isu_mega_menu'){
				$mega_menu = get_post($menu_item->object_id);
				$uri = current(get_post_meta($mega_menu->ID,'isu_mega_menu_uri'));
				$theme_menu[$menu_item->ID]['megamenu_content'] = do_shortcode($mega_menu->post_content );
				$theme_menu[$menu_item->ID]['uri'] = $uri ?: '#';
			}
		}

		$tree = theme_navwalker::buildTree( $theme_menu );

		return $tree;
	}

	static function cleanTree(&$item){
		if( isset($item['pages'])){
			theme_navwalker::cleanTree($item['pages']);
		}
		unset($item['id']);
		unset($item['parent_id']);
	}

	static function buildTree( array &$elements, $parentId = 0 ) {
		$branch = array();
		foreach ( $elements as $id=>$element ) {
			if ( $element['parent_id'] == $parentId ) {
				$children = theme_navwalker::buildTree( $elements, $id );
				if ( $children ) {
					$element['pages']        = $children;
					$element['showchildren'] = true;
				}
				unset( $element['parent_id'] );
				$branch[ $id ] = $element;
			}
		}

		return $branch;
	}
}