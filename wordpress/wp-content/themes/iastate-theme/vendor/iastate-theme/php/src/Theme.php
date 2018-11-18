<?php

namespace IastateTheme;

use InvalidArgumentException;
use RuntimeException;

/**
 * IASTATE Theme.
 *
 * @version 2.0.12
 *
 * @link //theme.iastate.edu/
 */
class Theme
{
	const VERSION = '2.0.12';

	/**
	 * Configuration options for PHP IASTATE theme.
	 *
	 * @var array
	 *
	 * @link //php.theme.iastate.edu/configure/options
	 * @since 2.0.0
	 */
	protected $options = array();

	/**
	 * Create new theme object.
	 *
	 * @param array|string $pageTitleOrOptions Either the page_title or an options array
	 * @param array        $options            (optional) Array of options if first argument is page_title
	 *
	 * @since 2.0.0
	 */
	public function __construct($pageTitleOrOptions = null, $options = array())
	{
		$this->configure();
		$this->init();
		if (!is_array($pageTitleOrOptions))
		{
			$options['page_title'] = $pageTitleOrOptions;
		}
		else {
			$options = $pageTitleOrOptions;
		}
		if ($options) {
			$this->setOptions($options);
		}
	}

	/**
	 * Default theme configuration.
	 *
	 * @since 2.0.0
	 */
	protected function configure()
	{
		$this->setOptions(array(

			// show
			'show_header'				=> true,
			'show_navbar_iastate'		=> true,
			'show_navbar_site'			=> true,
			'show_search_box'			=> true,
			'show_site_title'			=> true,
			'show_site_links'			=> true,
			'show_navs'					=> true,
			'show_navbar_menu'			=> true,
			'show_breadcrumbs'			=> false,
			'show_page_title'			=> true,
			'show_footer'				=> true,

			// search
			'search_action'				=> '//google.iastate.edu/search',
			'search_client'				=> 'default_frontend',
			'search_output'				=> 'xml_no_dtd',
			'search_placeholder'		=> 'Search',
			'search_site'				=> isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST']:'localhost',
			'search_submit'				=> 'Search',
			'search_style'				=> 'default_frontend',

			// navs
			'site_links'				=> null,
			'navbar_menu'				=> null,
			'navbar_caps'				=> true,
			'navbar_menu_hover'			=> true,
			'navbar_menu_affix'			=> true,
			'navbar_menu_justify'		=> false,

			'request_uri'				=> null,
			'right_sidebar'				=> null,
			'right_nav'					=> null,
			'right_nav_scrollspy'		=> false,
			'right_nav_affix'			=> false,
			'right_nav_collapse'		=> false,

			// apps
			'authorization_callback'	=> null,
			'route_callback'			=> null,
			'translator_callback'		=> null,

			// titles
			'head_title' => function (Theme $theme) {
				$titles = array();
				foreach (array('page', 'site', 'org') as $type)
				{
					if (($part = $theme->getOption($type .'_title')) != null)
					{
						if (count($titles) && $titles[count($titles) - 1] == $part)
						{
							continue;
						}
						$titles[] = $part;
					}
				}
				return $titles;
			},
			'title_separator'			=> ' • ',
			'org_title'					=> 'Iowa State University',
			'org_url'					=> 'http://www.iastate.edu/',
			'site_title'				=> null,
			'site_url'					=> '',
			'page_title'				=> null,

			// site
			'full_width'				=> false,
			'navs'						=> '',
			'post_nav'					=> '',
			'main_flush_top'			=> false,

			'base_path' => '',
			'asset_path' => '',
			'module_asset_path' => '',
			'theme_asset_path' => '',

			// <head>
			'head_meta' => array(
				'charset' => 'utf-8',
				'x_ua_compatible' => array(
					'content' => 'IE=edge,chrome=1',
					'key_value' => 'X-UA-Compatible',
					'key_type' => 'http-equiv',
				),
				'viewport' => 'width=device-width,initial-scale=1',
				'format-detection' => 'telephone=no',
			),
			// <head> <link>s
			'head_link' => array(
// 				'legacy' => [
// 					'href' => '{{theme_asset_path}}/css/iastate.legacy.min.css',
// 					'order' => -2,
// 				],
				'nimbus_sans' => array(
					'href' => 'https://cdn.theme.iastate.edu/nimbus-sans/css/nimbus-sans.css',
				),
				'merriweather' => array(
					'href' => 'https://cdn.theme.iastate.edu/merriweather/css/merriweather.css',
				),
				'font_awesome' => array(
					'href' => 'https://cdn.theme.iastate.edu/font-awesome/css/font-awesome.css',
				),
				'iastate' => array(
					'href' => '{{theme_asset_path}}/css/iastate.min.css?v='. self::VERSION,
				),
				'favicon' => array(
					'href' => 'https://cdn.theme.iastate.edu/favicon/favicon.ico',
					'rel' => 'icon',
					'type' => 'image/x-icon',
				),
				'faviconpng' => array(
					'href' => 'https://cdn.theme.iastate.edu/favicon/favicon.png',
					'rel' => 'icon',
					'type' => '',
				),
				'faviconappletouch' => array(
					'href' => 'https://cdn.theme.iastate.edu/favicon/apple-touch-icon.png',
					'rel' => 'icon',
				),

			),
			// <head> <style>s
			'head_style' => array(),
			// <head> <script>s
			'head_script' => array(
				'file' => array(),
				'script' => array(),
			),
			// </body> (inline) <script>s
			'inline_script' => array(
				'file' => array(
					'jquery' => '//code.jquery.com/jquery-1.11.3.min.js',
					'bootstrapjs' => '{{theme_asset_path}}/js/vendor/bootstrap.min.js',
					'sub_menu' => '{{theme_asset_path}}/js/vendor/bootstrap-submenu.min.js',
					'iastate' => '{{theme_asset_path}}/js/iastate.js',
				),
				'script' => array(),
			),

			'render_tags' => array(
				'email' => function ($str, Theme $theme)
				{
					list(, $email, $label) = array_pad(explode('|', $str), 3, null);
					return $theme->email($email, $label);
				},
			),

			// sign ons
			'signons' => array(
				'accessplus' => array(
					'title' => 'AccessPlus',
					'url' => 'https://accessplus.iastate.edu/',
				),
				'canvas' => array(
					'title' => 'Canvas',
					'url' => 'https://canvas.iastate.edu/',
				),
				'cybox' => array(
					'title' => 'CyBox',
					'url' => 'https://iastate.box.com/',
				),
				'cymail' => array(
					'title' => 'CyMail',
					'url' => 'http://cymail.iastate.edu/',
				),
				'okta' => array(
					'title' => 'Okta',
					'url' => 'https://login.iastate.edu/',
				),
				'outlook' => array(
					'title' => 'Outlook',
					'url' => 'https://outlook.iastate.edu/',
				),
				'more' => array(
					'title' => 'More Sign Ons...',
					'url' => 'https://web.iastate.edu/signons/',
				),
			),

			// carousel
			'show_carousel' => false,
			'carousel' => array(
				'content' => array(
				//	[
				//		'src' => '/img/url/imgname.jpg',
				//		'alt' => 'Visual Description of Image',
				//		'url' => '/route', //image link, null for no link
				//		'title' => 'Image/Link Title', //for caption
				//		'description' => 'Image/Link Description', //for caption
				//	],
				),
				'show_side_buttons' => false,
				'show_captions' => false,
			),

			// footer
			'footer_associates' => array(
				array(
					'label' => 'College of Corpus Callosum',
					'url' => '//sample.theme.iastate.edu',
				),
				array(
					'label' => 'Department of Lorem Ipsum',
					'url' => '//sample.theme.iastate.edu',
				),
			),
			'footer_contact' => array(
				'address' => array(
					'1234 Lancelot Drive',	// line 1
					'0001 Elaine Hall',		// line 2
					'Ames, IA 50011',		// ...
				),
				'address_url' => 'https://goo.gl/maps/89RUHcCFt652',	// url to link address to map (Google, Bing, FP&M, etc.)
				'email' => array(
					'deptaccount@iastate.edu',	// line 1, add more, just like address
				),
				'phone' => array(
					'515-294-9999',			// line 1, add more, just like address
				),
				'fax' => array(
					'515-294-9999',			// line 1, add more, just like address
				),
			),
			'show_social_labels' => true,
			'footer_social' => array(
				array(
					'label' => 'Social Media Directory',
					'url' => 'http://web.iastate.edu/social/',
				),
			),
			'footer_legal' => array(
				'statement' => 'Copyright &copy; 1995-<script>document.write(new Date().getFullYear())</script><br>
					Iowa State University<br>
					of Science and Technology<br>
					All rights reserved.',
				'links' => array(
					array(
						'label' => 'Non-discrimination Policy',
						'url' => 'http://www.policy.iastate.edu/policy/discrimination',
					),
					array(
						'label' => 'Privacy Policy',
						'url' => 'http://www.policy.iastate.edu/electronicprivacy',
					),
					array(
						'label' => 'Digital Access &amp; Accessibility',
						'url' => 'http://digitalaccess.iastate.edu',
					),
				),
			),
		));
	}

	/**
	 * Place theme-specific defaults here when extending Theme for sub-sites
	 * This code is called at the beginning of __construct and can be overwritten by options provided to the constructor.
	 *
	 * @return Theme $this
	 *
	 * @since 2.0.0
	 */
	protected function init()
	{
		return $this;
	}

	/**
	 * Convert an option name from camelCase to under_score.
	 *
	 * @param string $name
	 *
	 * @return string
	 *
	 * @deprecated -=LEGACY=-
	 * @since 2.0.0
	 */
	protected function inflectOptionName($name)
	{
		if (strtolower($name) != $name)
		{
			$name = preg_replace_callback('/[A-Z]/', function ($m)
			{
				return '_'. strtolower($m[0]);
			}, $name);
		}
		return $name;
	}

	/**
	 * Check to see if theme has option set.
	 *
	 * @param string $name
	 *
	 * @return bool
	 *
	 * @since 2.0.0
	 */
	public function hasOption($name)
	{
		return array_key_exists($this->inflectOptionName($name), $this->options);
	}

	/**
	 * Get an option. Optionally return a preset default value in case option is not set or is null.
	 *
	 * @param string     $name
	 * @param mixed|null $default
	 *
	 * @return mixed|null
	 *
	 * @since 2.0.0
	 */
	public function getOption($name, $default = null)
	{
		$name = $this->inflectOptionName($name);
		if (isset($this->options[$name]))
		{
			return $this->options[$name];
		}
		return $default;
	}

	/**
	 * Set an option. Will merge value with existing by default if value is an array.
	 *
	 * @param string $name
	 * @param mixed  $value
	 * @param bool   $reset replace existing value if true instead of merging
	 *
	 * @return Theme $this
	 *
	 * @link http://php.theme.iastate.edu/configure
	 * @since 2.0.0
	 */
	public function setOption($name, $value, $reset = false)
	{
		$name = $this->inflectOptionName($name);
		if (!$reset && isset($this->options[$name]) && is_array($this->options[$name]) && is_array($value))
		{
			$value = $this->merge($this->options[$name], $value);
		}
		$this->options[$name] = $value;
		return $this;
	}

	/**
	 * Get all options (for debugging).
	 *
	 * @return array
	 *
	 * @since 2.0.0
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Set multiple options.
	 *
	 * @param array $options
	 *
	 * @return Theme $this
	 *
	 * @link http://php.theme.iastate.edu/configure
	 * @since 2.0.0
	 */
	public function setOptions(array $options)
	{
		if (!empty($options))
		{
			foreach ($options as $name => $value)
			{
				if($name == 'carousel' || (substr($name, 0, 7) == 'footer_' && (is_array($value) && !empty($value))))
				{
					$this->setOption($name, $value, true);
					continue;
				}
				$this->setOption($name, $value);
			}
		}
		return $this;
	}
	
	/**
	 * Merge two arrays recursively.
	 * If an integer key exists in both arrays, the value from the second array will be appended the the first array.
	 * If both values are arrays, they are merged together, else the value of the second array overwrites the one of the first array.
	 *
	 * @param array $a
	 * @param array $b
	 *
	 * @return array
	 *
	 * @since 2.0.0
	 */
	protected function merge(array $a, array $b)
	{
		foreach ($b as $key => $value)
		{
			if (array_key_exists($key, $a))
			{
				if (is_int($key))
				{
					$a[] = $value;
				}
				elseif (is_array($value) && is_array($a[$key]))
				{
					$a[$key] = $this->merge($a[$key], $value);
				}
				else
				{
					$a[$key] = $value;
				}
			}
			else
			{
				$a[$key] = $value;
			}
		}
		return $a;
	}
	
	/**
	 * Add a style asset to be rendered within the <head> element. Can be a url or inline style content.
	 *
	 * @param array|string $spec $spec Either a url or inline style (do NOT include <style> tags). Can also be an array:
	 *                           - if $mode is 'link'
	 *                           -- an array of html element attributes (href, media, rel, etc.) and order
	 *                           - if $mode is 'style'
	 *                           -- content: the inline style content (do NOT include <style> tags)
	 *                           -- attributes: an array of html element attributes
	 * @param string       $mode Either 'link' for urls or 'style' for inline styles
	 *
	 * @throws InvalidArgumentException if incorrect $mode provided
	 *
	 * @return Theme $this
	 *
	 * @since 2.0.0
	 */
	public function addStyle($spec, $mode = 'link')
	{
		if (is_string($spec) && strlen(trim($spec)) == 0)
		{
			return $this;
		}
		if ($mode != 'link' && $mode != 'style')
		{
			throw new InvalidArgumentException(sprintf(
				"Expected \$mode to be 'link' or 'style', got '%s' instead", $mode
			));
		}
		$this->setOption('head_' . $mode, array(
			md5(serialize($spec)) => $spec,
		));
		return $this;
	}

	/**
	 * Add a script asset to be rendered within the <head> element or within the <body> element
	 * Can be a URL or inline script content.
	 *
	 * @param array|string $spec     Either a URL or inline script (do NOT include <script> tags). Can also be an array:
	 *                               - if $mode is 'file'
	 *                               -- an array of HTML element attributes (src, type, etc.) and order
	 *                               - if $mode is 'script'
	 *                               -- content: the inline script content (do NOT include <script> tags)
	 *                               -- attributes: an array of html element attributes
	 * @param string       $mode     Either 'file' for urls or 'script' for inline scripts
	 * @param string       $position Either 'head' to place inside <head> or 'inline' to append to <body>
	 *
	 * @throws InvalidArgumentException if incorrect $mode or $position is provided
	 *
	 * @return Theme $this
	 *
	 * @since 2.0.0
	 */
	public function addScript($spec, $mode = 'file', $position = 'inline')
	{
		if (is_string($spec) && strlen(trim($spec)) == 0)
		{
			return $this;
		}
		if ($mode != 'file' && $mode != 'script')
		{
			throw new InvalidArgumentException(sprintf(
				"Expected \$mode to be 'file' or 'script', got '%s' instead", $mode
			));
		}
		if ($position != 'head' && $position != 'inline')
		{
			throw new InvalidArgumentException(sprintf(
				"Expected \$position to be 'head' or 'inline', got '%s' instead", $position
			));
		}
		$this->setOption($position . '_script', array(
			$mode => array(
				md5(serialize($spec)) => $spec,
			),
		));
		return $this;
	}

	/**
	 * Render theme head (pre-content theme functions).
	 *
	 * @return Theme $this
	 *
	 * @since 2.0.0
	 */
	public function drawHeader()
	{
		echo $this->renderHtmlStart();
		echo $this->renderHead();
		echo $this->renderBodyStart();
		echo $this->renderSkipNavigation();
		echo $this->renderHeader();
		echo $this->renderNavs();
		echo $this->renderPostNav();
		echo $this->renderContentStart();
		echo $this->renderPageTitle();
		return $this;
	}

	/**
	 * Render theme foot (post-content theme functions).
	 *
	 * @return Theme $this
	 *
	 * @since 2.0.0
	 */
	public function drawFooter()
	{
		echo $this->renderContentEnd();
		echo $this->renderFooter();
		echo $this->renderLoadingBar();
		echo $this->renderInlineScript();
		echo $this->renderBodyEnd();
		echo $this->renderHtmlEnd();
		return $this;
	}

	/**
	 * Render <!DOCTYPE> and <html>.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHtmlStart()
	{
		return <<<HTML
<!DOCTYPE html>
<html lang="en">

HTML;
	}

	/**
	 * Render <head>.
	 *
	 * @return string
	 */
	public function renderHead()
	{
		$arr = array(
			$this->renderHeadMeta(),
			$this->renderHeadTitle(),
			$this->renderHeadLink(),
			$this->renderHeadStyle(),
			$this->renderHeadScript(),
		);
		$imploded = implode("\n", $arr);
		return <<<HTML
<head>
{$imploded}</head>

HTML;
	}

	/**
	 * Render <head> <meta>s.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHeadMeta()
	{
		$items = $this->getOption('head_meta', array());
		$index = $this->sort($items);
		$html = array();
		foreach ($index as $name)
		{
			$spec = $items[$name];
			if (is_callable($spec))
			{
				$spec = call_user_func($spec, $this);
			}
			if (is_null($spec))
			{
				continue;
			}
			if (is_string($spec))
			{
				if ($name == 'charset')
				{
					$attr = array(
						'charset' => $spec,
					);
				}
				else
				{
					$attr = array(
						'content' => $spec,
						'name' => $name,
					);
				}
			}
			else
			{
				$attr = array(
					'content' => $spec['content'],
					$spec['key_type'] => $spec['key_value'],
				);
			}
			$html[] = "\t<meta " . $this->createAttributesString($attr) . ">";
		}
		return implode("\n", $html);
	}

	/**
	 * Render <head> <title>.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHeadTitle()
	{
		$title = $this->getOption('head_title');
		if (is_callable($title))
		{
			$title = call_user_func($title, $this);
		}
		if (is_array($title))
		{
			$title = implode($this->getOption('title_separator'), $title);
		}
		return "\t<title>" . $this->escape($title) . "</title>";
	}
	
	/**
	 * Render <head> <link>s.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHeadLink()
	{
		$items = $this->getOption('head_link', array());
		$index = $this->sort($items);
		$html = array();
		foreach ($index as $name)
		{
			$spec = $items[$name];
			if (is_callable($spec))
			{
				$spec = call_user_func($spec, $this);
			}
			if (is_null($spec))
			{
				continue;
			}
			if (is_string($spec))
			{
				$spec = array(
					'href' => $spec,
					'media' => 'all',
					'rel' => 'stylesheet',
				);
			}
			if (isset($spec['href']))
			{
				$spec['href'] = $this->render($spec['href']);
			}
			if (!isset($spec['rel']))
			{
				$spec['rel'] = 'stylesheet';
			}
			unset($spec['order']);
			$html[] = "\t<link " . $this->createAttributesString($spec) . ">";
		}
		return implode("\n", $html);
	}

	/**
	 * Render <head> <style>s.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHeadStyle()
	{
		$items = $this->getOption('head_style', array());
		$index = $this->sort($items);
		$html = array();
		foreach ($index as $name)
		{
			$spec = $items[$name];
			if (is_callable($spec))
			{
				$spec = call_user_func($spec, $this);
			}
			if (is_null($spec))
			{
				continue;
			}
			if (is_string($spec))
			{
				$spec = array(
					'content' => $spec,
					'attributes' => array(),
				);
			}
			$html[] = "\t<style " . $this->createAttributesString($spec['attributes']) . ">" . $spec['content'] . "</style>";
		}
		return implode("\n", $html);
	}

	/**
	 * Render <head> <script>s.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHeadScript()
	{
		return $this->renderScript($this->getOption('head_script', array()));
	}

	/**
	 * Render </body> <script>s.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderInlineScript()
	{
		return $this->renderScript($this->getOption('inline_script', array()));
	}

	/**
	 * Render <script> tags from the given config options.
	 *
	 * @param array $config
	 *
	 * @throws InvalidArgumentException if incorrect $mode is found
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderScript(array $config)
	{
		$html = array();
		foreach ($config as $mode => $items)
		{
			if ($mode != 'file' && $mode != 'script')
			{
				throw new InvalidArgumentException(sprintf(
					"Expected \$mode to be 'file' or 'script', got '%s' instead", $mode
				));
			}
			$index = $this->sort($items);
			foreach ($index as $name)
			{
				$spec = $items[$name];
				if (is_callable($spec))
				{
					$spec = call_user_func($spec, $this);
				}
				if (is_null($spec))
				{
					continue;
				}
				if ($mode == 'file')
				{
					if (is_string($spec))
					{
						$spec = array(
							'src' => $spec,
						);
					}
					if (isset($spec['src']))
					{
						$spec['src'] = $this->render($spec['src']);
					}
					unset($spec['order']);
					$html[] = "\t<script " . $this->createAttributesString($spec) . "></script>";
				}
				elseif ($mode == 'script')
				{
					if (is_string($spec))
					{
						$spec = array(
							'content' => $spec,
							'attributes' => array(),
						);
					}
					$html[] = "\t<script " . $this->createAttributesString($spec['attributes']) . ">" . $spec['content'] . "</script>";
				}
			}
		}
		return implode("\n", $html);
	}

	/**
	 * Render <body>.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderBodyStart()
	{
		return <<<HTML

<body>
HTML;
	}

	/**
	 * Render skip-nav.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderSkipNavigation()
	{
		return <<<HTML


<!-- skip-nav -->
<div class="skip-nav"><a href="#main-content">Skip to main content</a></div>
<!-- /skip-nav -->

HTML;
	}

	/**
	 * Render <header>.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHeader()
	{
		if ($this->getOption('show_header') !== true)
		{
			return '';
		}
		return implode("\n", array(
			$this->renderHeaderStart(),
			$this->renderNavbarIastate(),
			$this->renderNavbarSite(),
			$this->renderHeaderEnd(),
		));
	}

	/**
	 * Render <header> start.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHeaderStart()
	{
		return <<<HTML

<!-- header -->
<header>
HTML;
	}

	/**
	 * Get container class.
	 *
	 * @return string
	 *
	 * @since 2.0.6
	 */
	public function getContainer()
	{
		$container = 'container';
		if ($this->getOption('full_width'))
		{
			$container .= '-fluid';
		}
		return $container;
	}

	/**
	 * Render container start.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderContainerStart()
	{
		return <<<HTML
<div class="{$this->getContainer()}">
HTML;
	}

	/**
	 * Render container end.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderContainerEnd()
	{
		return <<<HTML
</div>
HTML;
	}

	/**
	 * Render navbar-iastate.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarIastate()
	{
		if ($this->getOption('show_navbar_iastate') !== true)
		{
			return '';
		}
		return implode("\n", array(
			$this->renderNavbarIastateStart(),
			$this->renderNavbarIastateLeft(),
			$this->renderNavbarIastateRight(),
			$this->renderNavbarIastateEnd(),
		));
	}

	/**
	 * Render navbar-iastate start.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarIastateStart() {
		return <<<HTML

<!-- navbar-iastate -->
<nav class="navbar-iastate">
	{$this->renderContainerStart()}
HTML;
	}

	/**
	 * Render navbar-iastate left.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarIastateLeft()
	{
		$index = array();
		foreach (range('A', 'Z') as $l)
		{
			$index[] = "\n\t\t\t\t\t<li><a href=\"http://www.iastate.edu/index/" . $l . "/\">" . $l . "</a></li>";
		}
		$index = implode('', $index);
		return <<<HTML

		<!-- navbar-iastate-left -->
		<ul class="nav navbar-nav navbar-iastate-left">
			<li><a href='http://www.iastate.edu'>iastate.edu</a></li>
			<li class="dropdown dropdown-hover isu-index">
				<a href="http://www.iastate.edu/index/A" class="dropdown-toggle" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false">Index</a>
				<ul class="dropdown-menu isu-index-alpha">{$index}
				</ul>
			</li>
		</ul>
		<!-- /navbar-iastate-left -->
HTML;
	}

	/**
	 * Render navbar-iastate right.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarIastateRight()
	{
		$linkList = "";
		foreach ($this->getOption('signons') as $link)
		{
			$linkList .= "\n\t\t\t\t\t" . '<li><a href="' . $link['url'] . '">' . $link['title'] . '</a></li>';
		}
		return <<<HTML

		<!-- navbar-iastate-right -->
		<ul class="nav navbar-nav navbar-right navbar-iastate-right">
			<li><a href="http://info.iastate.edu/">Directory</a></li>
			<li><a href="http://www.fpm.iastate.edu/maps/">Maps</a></li>
			<li><a href="http://web.iastate.edu/safety/">Safety</a></li>
			<li class="dropdown dropdown-hover">
				<a href="//web.iastate.edu/signons/" class="dropdown-toggle" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false">Sign Ons</a>
				<ul class="dropdown-menu">{$linkList}
				</ul>
			</li>
		</ul>
		<!-- /navbar-iastate-right -->
HTML;
	}

	/**
	 * Render /navbar-iastate.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarIastateEnd()
	{
		return <<<HTML

	</div>
</nav>
<!-- /navbar-iastate -->
HTML;
	}

	/**
	 * Render navbar-site.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarSite()
	{
		if ($this->getOption('show_navbar_site') !== true)
		{
			return '';
		}
		return implode("\n", array(
			$this->renderNavbarSiteStart(),
			$this->renderNavbarSiteWordmark(),
			$this->renderNavbarSiteHeader(),
			$this->renderNavbarSiteSearch(),
			$this->renderNavbarSiteLinks(),
			$this->renderNavbarSiteEnd(),
		));
	}

	/**
	 * Render navbar-site start.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarSiteStart()
	{
		return <<<HTML

<!-- navbar-site -->
<nav class="navbar-site">
	{$this->renderContainerStart()}

HTML;
	}

	/**
	 * Render navbar-site-wordmark.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarSiteWordmark()
	{
		$html = <<<HTML
		<div class="navbar-site-wordmark">
HTML;
		if ($this->getOption('site_url') !== null)
		{
			$html .= "\n\t\t\t<a href=\"" . $this->escape($this->render($this->getOption('site_url')) ? : '/') . '" title="Home" class="wordmark-unit">';
		}
		$html .= <<<HTML

				<span class="wordmark-isu">Iowa State University</span>
HTML;
		if ($this->getOption('show_site_title') == true)
		{
			$html .= "\n\t\t\t\t<span class=\"wordmark-unit-title\">" . $this->escape($this->getOption('site_title')) . "</span>";
		}
		$html .= <<<HTML

			</a>
		</div>
HTML;
		return $html;
	}

	/**
	 * Render navbar-site-header.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarSiteHeader()
	{
		return <<<HTML

		<div class="navbar-header visible-xs visible-sm">
			<button id="navbar-menu-button" type="button" class="navbar-toggle navbar-toggle-left collapsed" data-toggle="collapse" data-target="#navbar-menu-collapse, #navbar-site-links-collapse" aria-expanded="false">
				<span class="navbar-toggle-icon menu-icon">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</span>
				<span class="navbar-toggle-label">
					Menu <span class="sr-only">Toggle</span>
				</span>
			</button>
			<button id="navbar-search-button" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-site-search-collapse" aria-expanded="false">
				<span class="navbar-toggle-icon search-icon"></span>
				<span class="navbar-toggle-label">
					Search <span class="sr-only">Toggle</span>
				</span>
			</button>
		</div>
HTML;
	}

	/**
	 * Render navbar-site-search.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarSiteSearch()
	{
		if ($this->getOption('show_search_box') !== true)
		{
			return '';
		}
		$html = array();
		$html[] = "\n\t\t" . '<div class="navbar-site-info collapse navbar-collapse" id="navbar-site-search-collapse">';
		$html[] = "\t\t\t" . '<form action="' . $this->getOption('search_action') . '" class="navbar-site-search" method="GET" role="search">';
		$html[] = "\t\t\t\t" . '<label for="search-input" class="sr-only">Search</label>';
		if (($value = $this->getOption('search_output')))
		{
			$html[] = "\t\t\t\t" . '<input name="output" type="hidden" value="' . $this->escape($value) . '">';
		}
		if (($value = $this->getOption('search_client')))
		{
			$html[] = "\t\t\t\t" . '<input name="client" type="hidden" value="' . $this->escape($value) . '">';
		}
		if (($value = $this->getOption('search_site')))
		{
			$html[] = "\t\t\t\t" . '<input name="sitesearch" type="hidden" value="' . $this->escape($value) . '">';
		}
		if (($value = $this->getOption('search_style')))
		{
			$html[] = "\t\t\t\t" . '<input name="proxystylesheet" type="hidden" value="' . $this->escape($value) . '">';
		}
		$html[] = "\t\t\t\t" . '<input name="q" id="search-input" aria-label="Text input for search" title="Search" placeholder="' . $this->escape($this->getOption('search_placeholder')) . '" tabindex="0" type="text" class="form-control">';
		$html[] = "\t\t\t\t" . '<input class="hidden" title="Submit" type="submit" value="' . $this->escape($this->getOption('search_submit')) . '">';
		$html[] = "\t\t\t\t" . '<span class="search-icon"></span>';
		$html[] = "\t\t\t" . '</form>';
		$html[] = "\t\t" . '</div>';
		return implode("\n", $html);
	}

	/**
	 * Render navbar-site-links.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarSiteLinks()
	{
		if ($this->getOption('show_site_links') !== true)
		{
			return '';
		}
		$navCaps = $this->getOption('navbar_caps') ? ' navbar-caps' : '';
		$html = array();
		$html[] = "\n\t\t" . '<div class="navbar-site-info collapse navbar-collapse'.$navCaps.'" id="navbar-site-links-collapse">';
		$html[] = "\t\t\t" . '<ul class="nav navbar-nav navbar-right">';
		$siteLinks = $this->getOption('site_links');
		if(!empty($siteLinks))
		{
			foreach ($this->getOption('site_links') as $link)
			{
				$icon = isset($link['icon']) ? '<span class="' . $link['icon'] . '" aria-hidden="true"></span> ' : '';
				if (isset($link['uri']) || isset($link['route']))
				{

					if (isset($link['uri']))
					{
						$link['href'] = $this->render($link['uri']);
					}
					elseif (isset($link['route']))
					{
						$link['href'] = $this->url($link['route']);
					}
					if ($this->getOption('navbar_menu_affix'))
					{
						$link['class'] = ' class="target-offset"';
					}
				}

				$html[] = "\t\t\t\t<li><a href=\"" . $link['href'] . "\"".$link['class'].">" . $icon . $this->escape($link['label']) . "</a></li>";
			}
		}
		$html[] = "\t\t\t" . '</ul>';
		$html[] = "\t\t" . '</div>';
		return implode("\n", $html);
	}

	/**
	 * Render navbar-site end.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarSiteEnd()
	{
		return <<<HTML

	{$this->renderContainerEnd()}
</nav>
<!-- /navbar-site -->
HTML;
	}

	/**
	 * Render </header> end.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHeaderEnd()
	{
		return <<<HTML

</header>
<!-- /header -->

HTML;
	}

	/**
	 * Render #navs.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavs()
	{
		if ($this->getOption('show_navs') !== true)
		{
			return '';
		}
		return implode("\n", array(
			$this->renderNavsStart(),
			$this->renderNavbarMenu(),
			$this->renderBreadcrumbs(),
			$this->renderNavsEnd(),
		));
	}

	/**
	 * Render #navs start.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavsStart()
	{
		return <<<HTML

<!-- navs -->
<div id="navs">

HTML;
	}

	/**
	 * Render navbar-menu.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavbarMenu()
	{
		if ($this->getOption('show_navbar_menu') !== true)
		{
			return '';
		}
		$navCaps = $this->getOption('navbar_caps') ? ' navbar-caps' : '';
		$navAffix = $this->getOption('navbar_menu_affix') ? ' navbar-menu-affix' : '';
		return <<<HTML
<!-- navbar-menu -->
<div class="navbar-menu-wrapper">
<nav class="navbar navbar-menu navbar-default navbar-static-top no-border{$navCaps}{$navAffix}" role="navigation">
	{$this->renderContainerStart()}
		<div class="collapse navbar-collapse" id="navbar-menu-collapse">
			{$this->renderNav($this->getOption('navbar_menu'))}
		</div>
	{$this->renderContainerEnd()}
</nav>
</div>
<!-- /navbar-menu -->
HTML;
	}

	/**
	 * Calculate active
	 * Returns an array containing the percentage match for each page.
	 * The match for a node is the maximum of the node and all of its descendant node matches.
	 *
	 * @param array $page
	 *
	 * @return array
	 *
	 * @since 2.0.0
	 */
	protected function calculateActiveRating($page)
	{
		$active = array(
			'value' => 0,
			'pages' => array(),
		);
		if (!isset($page['noselect']) || $page['noselect'] !== true)
		{
			$reqUri = $this->getOption('request_uri', $_SERVER['REQUEST_URI']);
			if (isset($page['uri']))
			{
				$uri = $this->render($page['uri']);
			}
			elseif (isset($page['route']))
			{
				$uri = $this->url($page['route']);
			}
			else
			{
				$uri = '';
			}
			$value = 0;
			if ($uri == $reqUri)
			{
				$value = 100;
			}
			elseif ($uri != '' && strpos($reqUri, $uri) === 0)
			{
				$value = round(100 * strlen($uri) / strlen($reqUri));
			}
			if (isset($page['pattern']))
			{
				if (preg_match($page['pattern'], $reqUri))
				{
					$value = 100;
				}
			}
			if ($value > 0 && isset($page['nopattern']))
			{
				if (preg_match($page['nopattern'], $reqUri))
				{
					$value = 0;
				}
			}
			if (!empty($page['pages']))
			{
				foreach ($page['pages'] as $i => $child)
				{
					$active['pages'][$i] = $this->calculateActiveRating($child);
				}
				$value = max($value, max(array_map(function ($child)
				{
					return $child['value'];
				}, $active['pages'])));
			}
			$active['value'] = $value;
		}
		return $active;
	}

	/**
	 * Remove active (except for most 'active' pages within the tree).
	 *
	 * @param array $pages
	 *
	 * @since 2.0.0
	 */
	protected function activateMaxRating(&$pages)
	{
		if (empty($pages))
		{
			return;
		}
		$max = max(array_map(function ($page)
		{
			return $page['value'];
		}, $pages));
		foreach ($pages as &$page)
		{
			if ($page['value'] < $max)
			{
				$page['value'] = 0;
			}
			$this->activateMaxRating($page['pages']);
		}
	}

	/**
	 * Returns the keys of the given array sorted by the order property of each array item in ascending order.
	 * <code>
	 * print $this->sort([
	 *     'item1' => [
	 *         'label' => 'Item 1',
	 *     ],
	 *     'item2' => [
	 *         'label' => 'Item 2',
	 *         'order' => -1,
	 *     ],
	 * ]);
	 * // [
	 * //     'item2',
	 * //     'item1',
	 * // ]
	 * </code>.
	 *
	 * @param array $items
	 *
	 * @return array
	 *
	 * @since 2.0.0
	 */
	public function sort($items)
	{
		$index = array();
		$c = 0;
		foreach ($items as $i => $item)
		{
			if (is_array($item) && isset($item['order']))
			{
				$index[$i] = $item['order'];
			}
			else
			{
				$index[$i] = $c++;
			}
		}
		asort($index);
		return array_keys($index);
	}

	/**
	 * Render menu.
	 *
	 * @param $pages
	 * @param int   $depth
	 * @param array $activePages
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNav($pages, $depth = 0, $activePages = array())
	{
		if (empty($pages))
		{
			return '';
		}
		$index = $this->sort($pages);
		if ($depth == 0)
		{
			$activePages = array();
			foreach ($pages as $i => $page)
			{
				$activePages[$i] = $this->calculateActiveRating($page);
			}
			$this->activateMaxRating($activePages);
		}
		$html = array();
		$tabDepth = str_repeat("\t", $depth*2+1);
		foreach ($index as $i)
		{
			$page = $pages[$i];
			$active = $activePages[$i]['value'] > 0;
			// legacy support for 'showchildren'
			if (isset($page['showchildren']) && !isset($page['show_children']))
			{
				$page['show_children'] = $page['showchildren'];
			}
			$showChildren = isset($page['show_children']) && $page['show_children'];
			// legacy support for 'roles' and 'permissions'
			if (isset($page['roles']) && !isset($page['allowed_roles']))
			{
				$page['allowed_roles'] = $page['roles'];
			}
			if (isset($page['permissions']) && !isset($page['allowed_permissions']))
			{
				$page['allowed_permissions'] = $page['permissions'];
			}
			// perform authorization check to see if page visible
			if (isset($page['allowed_roles']) || isset($page['allowed_permissions']))
			{
				$allowedByRole = isset($page['allowed_roles']) && $this->isAllowed($page['allowed_roles'], 'role');
				$allowedByPermission = isset($page['allowed_permissions']) && $this->isAllowed($page['allowed_permissions'], 'permission');
				if (!$allowedByRole && !$allowedByPermission)
				{
					continue;
				}
			}
			if (isset($page['denied_roles']) || isset($page['denied_permissions']))
			{
				$deniedByRole = isset($page['denied_roles']) && $this->isAllowed($page['denied_roles'], 'role');
				$deniedByPermission = isset($page['denied_permissions']) && $this->isAllowed($page['denied_permissions'], 'permission');
				if ($deniedByRole || $deniedByPermission) {
					continue;
				}
			}
			$subNav = null;
			if (isset($page['pages']) && is_array($page['pages']) && ($showChildren || $active))
			{
				$subNav = $this->renderNav($page['pages'], $depth + 1, $activePages[$i]['pages']);
			}
			$megaMenu = null;
			if (isset($page['megamenu_content']) && !empty($page['megamenu_content']))
			{
				$megaMenu = $this->renderMegaMenu($page['megamenu_content']);
			}
			$nodeClass = array();
			if (!empty($subNav))
			{
				$nodeClass[] = 'dropdown';
				$nodeClass[] = ($this->getOption('navbar_menu_hover') ? 'dropdown-hover' : '');
				if ($depth > 0)
				{
					$nodeClass[] = 'dropdown-submenu';
				}
			}
			if (!empty($megaMenu))
			{
				$nodeClass[] = 'dropdown';
				$nodeClass[] = ($this->getOption('navbar_menu_hover') ? 'dropdown-hover' : '');
				$nodeClass[] = 'megamenu';
			}
			if ($active)
			{
				$leaf = true;
				foreach ($activePages[$i]['pages'] as $childPage)
				{
					if ($childPage['value'] > 0)
					{
						$leaf = false;
						break;
					}
				}
				$nodeClass[] = $leaf ? 'active' : 'active';
			}
			$nodeClass = array_unique(array_filter($nodeClass));
			$html[] = $tabDepth.'<li class="' . implode(' ', $nodeClass) . '">';
			$attr = array();
			foreach ($page as $key => $value)
			{
				$skip = array(
					'label',
					'escape',
					'translate',
					'uri',
					'route',
					'order',
					'pages',
					'show_children', 'showchildren',
					'pattern',
					'nopattern',
					'noselect',
					'icon',
					'roles', 'allowed_roles', 'denied_roles',
					'permissions', 'allowed_permissions', 'denied_permissions',
					'subnavs',
					'megamenu_content',
				);
				if (!in_array($key, $skip))
				{
					$attr[$key] = $value;
				}
			}
			if (isset($page['attributes']))
			{
				foreach ($page['attributes'] as $key => $value)
				{
					$attr[$key] = $value;
				}
			}
			if (isset($attr['class']))
			{
				$attr['class'] = explode(' ', $attr['class']);
			}
			else
			{
				$attr['class'] = array();
			}
			if (isset($page['uri']) || isset($page['route']))
			{
				if (isset($page['uri']))
				{
					$attr['href'] = $this->render($page['uri']);
				}
				elseif (isset($page['route']))
				{
					$attr['href'] = $this->url($page['route']);
				}
				// check here for "active" if you want that class on <a>s
			}
			if ($this->getOption('navbar_menu_affix'))
			{
				$attr['class'][] = 'target-offset';
			}
			$tag = 'a';
			if (($subNav && $depth == 0) || $megaMenu)
			{
				$attr['class'][] = 'target-offset dropdown-toggle';
				$attr['role'] = 'button';
				$attr['data-toggle'] = $this->getOption('navbar_menu_hover') ? '' : 'dropdown';
				$attr['aria-haspopup'] = 'true';
				$attr['aria-expanded'] = 'false';
				$attr['data-submenu'] = '';
			}
			if (($subNav || $megaMenu) && !$this->getOption('navbar_menu_hover'))
			{
				$attr['href'] = '#';
			}
			$attr['tabindex'] = '0';
			$label = isset($page['label']) ? $page['label'] : '';
			if (!isset($page['escape']) || $page['escape'] !== false)
			{
				$label = $this->escape($label);
			}
			if (!isset($page['translate']) || $page['translate'] !== false)
			{
				$label = $this->translate($label);
			}
			$icon = isset($page['icon']) ? '<span class="'.$page['icon'].'" aria-hidden="true"></span> ' : '';
			$attr['class'] = implode(' ', $attr['class']);
			$html[] = "\t".$tabDepth.'<' . $tag . ' ' . $this->createAttributesString($attr) . '>' . $icon . $label . '</' . $tag . '>';
			if (!empty($subNav))
			{
				$html[] = "\t".$tabDepth.'<a class="dropdown-toggle-mobile" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>';
				$html[] = $subNav;
			}
			if (!empty($megaMenu))
			{
				$html[] = "\t".$tabDepth.'<a class="dropdown-toggle-mobile" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>';
				$html[] = $megaMenu;
			}
			$html[] = "".$tabDepth.'</li>';
		}
		if (count($html) == 0)
		{
			return '';
		}
		$navClass = array();
		$navClass[] = $depth == 0 ? 'nav navbar-nav' : 'dropdown-menu';
		$role = $depth == 0 ? '' : 'role="menu"';
		array_unshift($html, preg_replace("/\t/", "", $tabDepth, 1)."<ul class=\"" . implode(' ', $navClass) . "\" ".$role.">");
		$html[] = preg_replace("/\t/", "", $tabDepth, 1).'</ul>';
		return implode("\n\t\t\t", $html);
	}

	/**
	 * Render breadcrumbs.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderBreadcrumbs() {
		if ($this->getOption('show_breadcrumbs') && $_SERVER['REQUEST_URI'] != '/')
		{
			$routes = explode('/', $_SERVER['REQUEST_URI']);
			$routeList = '';
			for($i = 0; $i < count($routes)-1; $i++)
			{
				$route = $routes[$i];
				$link = '/' . implode('/', array_slice($routes, $i));
				if ($route != '')
				{
					$routeList .= '<li';
					$routeList .= ($i < count($routes)-2) ? '><a href="'.$link.'">'.ucwords($route).'</a>' : ' class="active">'.$this->getOption('page_title');
					$routeList.='</li>';
				}
			}
			return <<<HTML

<!-- breadcrumb -->
<div class="breadcrumb-wrapper">
	<div class="{$this->getContainer()}">
		<ol class="breadcrumb">
			<li><a href="/" class="breadcrumb-item">Home</a></li>
			{$routeList}
		</ol>
	</div>
</div>
<!-- /breadcrumb -->
HTML;
		}
		return '';
	}

	/**
	 * Render #navs end.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderNavsEnd()
	{
		$html = $this->getOption('navs');
		return <<<HTML
{$html}
</div>
<!-- /navs -->


HTML;
	}

	/**
	 * Render #post-nav.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderPostNav()
	{
		$html = $this->getOption('post_nav');
		$carousel = $this->renderCarousel();
		$postnavContent = ($html ?: $carousel);
		return <<<HTML
<!-- post-nav -->
<div id="post-nav">
{$postnavContent}
</div>
<!-- /post-nav -->


HTML;
	}

	/**
	 * Render content start.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderContentStart()
	{
		$this->renderRightNav();
		$rightSidebar = '';
		$classes[] =  $this->getOption('main_flush_top') ? 'flush-top' : '';

		$classes = implode(' ', $classes);		

		if ($this->getOption('right_sidebar') !== null)
		{
			$rightSidebar = <<<HTML
<div class="row">
	<div class="col-md-9">
HTML;
		}

		return <<<HTML
<main role="main" id="main-content" class="$classes">
	{$this->renderContainerStart()}
		{$rightSidebar}
HTML;
	}

	/**
	 * Render the page_title.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderPageTitle()
	{
		if ($this->getOption('show_page_title') !== true)
		{
			return '';
		}
/* TODO: Ask Zak which to use for not ZF2 Theme
	    return <<<HTML
<nav class="navbar navbar-static-top navbar-clear">
	{$this->renderContainerStart()}
	<div class="nav navbar-nav navbar-header">
	    <h1>{$this->escape($this->getOption('page_title'))}</h1>
	</div>
	{$this->renderContainerEnd()}
</nav>
HTML;
 */
		return '<h1>' . $this->escape($this->getOption('page_title')) . '</h1>';
	}

	/**
	 * Render the closing tags for content.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderContentEnd()
	{
		$rightSidebar = '';
		if($this->getOption('right_sidebar') !== null)
		{
			$rightSidebar = <<<HTML
	</div>
	<div class="col-md-3">
		{$this->getOption('right_sidebar')}
	</div>
</div>
HTML;
		}
		return <<<HTML
		{$rightSidebar}
	{$this->renderContainerEnd()}
</main>
<!-- /main -->

HTML;
	}

	/**
	 * Render <footer>.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderFooter()
	{
		if ($this->getOption('show_footer') !== true)
		{
			return '';
		}
		return implode("\n\n", array(
			$this->renderFooterStart(),
			$this->renderFooterAssociates(),
			$this->renderFooterContact(),
			$this->renderFooterSocial(),
			$this->renderFooterLegal(),
			$this->renderFooterEnd(),
		));
	}

	/**
	 * Render <footer> start.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderFooterStart() {
		return <<<HTML

<!-- footer -->
<footer role="contentinfo">
	{$this->renderContainerStart()}
		<div class="row">
HTML;
	}

	/**
	 * Render footer-associates.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderFooterAssociates() {
		$linkList = "";
		foreach ($this->getOption('footer_associates') as $link)
		{
			$linkList .= "\n\t\t\t\t\t" . '<li><a href="' . $link['url'] . '">' . $link['label'] . '</a></li>';
		}
		return <<<HTML
			<!-- footer-associates -->
			<section class="footer-associates col-sm-12 col-md-3">
				<ul>
					<li><a href="http://www.iastate.edu"><img src="//cdn.theme.iastate.edu/img/isu-stacked.svg" class="wordmark-isu" alt="Iowa State University"></a></li>{$linkList}
				</ul>
			</section>
HTML;
	}

	/**
	 * Render footer-contact.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderFooterContact()
	{
		$info = $this->getOption('footer_contact');
		$printAddress = implode("<br>\n", $info['address']);
		$addressLink = $info['address_url'];
		$contactInfo = array();
		if(!empty($info['email']))
		{
			foreach ($info['email'] as $email)
			{
				$contactInfo[] = '<a href="mailto:'.$email.'">'.$email.'</a>';
			}
		}
		if(!empty($info['phone']))
		{
			foreach ($info['phone'] as $phone)
			{
				$contactInfo[] = '<a href="tel:'.$phone.'">'.$phone.' phone</a>';
			}
		}
		if(!empty($info['fax']))
		{
			foreach ($info['fax'] as $fax)
			{
				$contactInfo[] = $fax . ' fax';
			}
		}
		$contactInfo = implode(" <br>\n", $contactInfo);
		return <<<HTML
	<section class="footer-contact col-sm-12 col-md-3">
		<p>
			<strong>{$this->getOption('site_title')}</strong><br>
			<a href="{$addressLink}">{$printAddress}</a>
		</p>
		{$contactInfo}
	</section>	
HTML;
	}

	/**
	 * Render footer-social.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderFooterSocial()
	{
		$socialList = '';
		$rowBreak = 6;
		$numLinks = count($this->getOption('footer_social'));
		$labelClass = $this->getOption('show_social_labels') ? ' labeled' : ' unlabeled';
		$extras = '';
		if ($numLinks > $rowBreak && ($numLinks%$rowBreak) && !$this->getOption('show_social_labels'))
		{
			for($i = 0; $i < $rowBreak-$numLinks%$rowBreak; $i++)
			{
				$extras .= '<li></li>';
			}
		}
		foreach ($this->getOption('footer_social') as $social)
		{
			$label = $this->getOption('show_social_labels') ? $social['label']: '' ;
			$class=' fa-external-link-square';
			if (isset($social['html']) && $social['html'])
			{
				$socialList.= "<li>\n".$social['html']."\n</li>";
				continue;
			}
			if(isset($social['icon']) && $social['icon'])
			{
				$class = ' '.$social['icon'];
			}
			$title = $this->getOption('show_social_labels') ? '' : ' title="'.$social['label'].'"';
			$socialList .= <<<HTML

				<li><a href="{$social['url']}"{$title}><span class="fa{$class}" aria-label="hidden"></span>{$label}</a></li>
HTML;
		}
		return <<<HTML
			<!-- footer-social -->
			<section class="footer-social col-sm-12 col-md-3">
				<ul class="{$labelClass}">{$socialList}{$extras}
				</ul>
			</section>	
HTML;
	}

	/**
	 * Render footer-legal.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderFooterLegal()
	{
		$footerLegal = $this->getOption('footer_legal');
		$statement = $footerLegal['statement'];
		$linkList = "";
		foreach ($footerLegal['links'] as $link)
		{
			$linkList .= "\n\t\t\t\t\t" . '<li><a href="' . $link['url'] . '">' . $link['label'] . '</a></li>';
		}
		return <<<HTML
			<!-- footer-legal -->
			<section class="footer-legal col-sm-12 col-md-3">
				<p>
					{$statement}
				</p>
				<ul>{$linkList}
				</ul>
			</section>
HTML;
	}

	/**
	 * Render <footer> end.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderFooterEnd()
	{
		return <<<HTML
		</div>
	</div>
</footer>
<!-- /footer -->

HTML;
	}
	
	/**
	 * Render loading indicator.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderLoadingBar()
	{
		return <<<HTML

<!-- loading -->
<div id="loading" class="progress">
	<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
		Loading...
	</div>
</div>
<!-- /loading -->


HTML;
	}

	/**
	 * Render </body>.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderBodyEnd()
	{
		return "\n" . '</body>';
	}

	/**
	 * Render </html>.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderHtmlEnd()
	{
		return "\n" . '</html>';
	}

	/**
	 * Returns a spam-proof mailto link for the provided email address with an optional label.
	 *
	 * @param string $email email address or Net-ID
	 * @param string $label label for the mailto link (defaults to the email address)
	 *
	 * @throws InvalidArgumentException if empty email address given
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function email($email, $label = null)
	{
		if ($email == '')
		{
			throw new InvalidArgumentException('Email cannot be empty');
		}
		if (strpos($email, '@') === false)
		{
			$email .= '@iastate.edu';
		}
		$json = array_map('json_encode', explode('@', $email));
		$text = str_replace(array('@', '.'), array(' (at) ', ' (dot) '), $email);
		$noScript = $label ? "$label ($text)" : $text;
		$email = "[{$json[0]}, {$json[1]}].join('@')";
		$label = $label ? json_encode($label) : $email;

		return <<<HTML
<script>document.write('<a href="mailto:'+ {$email} +'">'+ {$label} +'</a>')</script><noscript>{$noScript}</noscript>
HTML;
	}

	/**
	 * Render a string replacing placeholders with variables.
	 * Place {{base_path}} or {{site_url}} in the string to replace with the respective configuration option.
	 * <code>
	 * echo $this->render('{{base_path}}/foo-bar');
	 * </code>.
	 *
	 * @param string $template
	 * @param array  $vars     custom variables
	 *
	 * @throws RuntimeException if a filter does not have a valid callback
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function render($template, $vars = array())
	{
		$vars = array_merge(array(
			'{{asset_path}}' => $this->getOption('asset_path'),
			'{{base_path}}' => $this->getOption('base_path'),
			'{{site_url}}' => $this->getOption('site_url'),
			'{{module_asset_path}}' => $this->getOption('module_asset_path'),
			'{{theme_asset_path}}' => $this->getOption('theme_asset_path'),
			'{{year}}' => date('Y'),
		), $vars);

		foreach ($this->getOption('render_tags', array()) as $name => $callback)
		{
			if (!is_callable($callback))
			{
				throw new RuntimeException("'render_tag.$name' option must be a valid callback");
			}
			$vars = array_replace($vars, $this->renderTag($template, '{{>' . $name, $callback));
			$vars = array_replace($vars, $this->renderTag($template, '{{> ' . $name, $callback));
		}

		$rendered = strtr($template, $vars);
		if (strpos($rendered, '{{') !== false && $rendered !== $template)
		{
			return $this->render($rendered, $vars);
		}
		return $rendered;
	}

	/**
	 * Render a particular tag and return an array containing the tag => string transformation.
	 *
	 * @param string   $template  The template to render
	 * @param string   $delimiter The tag opening delimiter
	 * @param callable $callback  The tag callback
	 *
	 * @return array
	 *
	 * @since 2.0.0
	 */
	protected function renderTag($template, $delimiter, $callback)
	{
		$vars = array();
		if (strpos($template, $delimiter) !== false)
		{
			$offset = 0;
			while (($pos = strpos($template, $delimiter, $offset)) !== false)
			{
				if (!in_array(substr($template, $pos + strlen($delimiter), 1), array('|', '}'), true))
				{
					$offset++;
					continue;
				}
				$length = strpos($template, '}}', $pos) + 2 - $pos;
				$match = substr($template, $pos, $length);
				$vars[$match] = call_user_func($callback, substr($match, strlen($delimiter), -2), $this);
				$offset = $pos + $length;
			}
		}
		return $vars;
	}

	/**
	 *  Create an html element attributes string from an array of attributes.
	 * <code>
	 * echo '<meta '. $theme->createAttributesString(['charset' => 'utf-8']) .'/>;
	 * // '<meta charset="utf-8"/>'
	 * </code>.
	 *
	 * @param array $attr
	 *
	 * @throws InvalidArgumentException for non-scalar attribute values
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function createAttributesString(array $attr = array())
	{
		$html = array();
		foreach ($attr as $name => $value)
		{
			if (!is_scalar($value))
			{
				throw new InvalidArgumentException(sprintf(
					"HTML attribute value for '%s' must be a scalar, got '%s' instead", $name, gettype($value)
				));
			}

			$html[] = $this->escape($name) . '="' . $this->escape($value) . '"';
		}
		return implode(' ', $html);
	}

	/**
	 * Escape an html snippet including single quotes and with utf-8 encoding.
	 *
	 * @param string $html
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function escape($html)
	{
		return htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Translate a string using the translator_callback option.
	 *
	 * @param string $text
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function translate($text)
	{
		$callback = $this->getOption('translator_callback');
		if (is_callable($callback))
		{
			return call_user_func($callback, $text);
		}
		return $text;
	}

	/**
	 * Generate a URL using the route_callback option.
	 *
	 * @param array|string $args
	 *
	 * @throws RuntimeException
	 *
	 * @return mixed
	 *
	 * @since 2.0.0
	 */
	public function url($args)
	{
		$callback = $this->getOption('route_callback');
		if (!is_callable($callback))
		{
			throw new RuntimeException("'route_callback' option must be a valid callback");
		}
		return call_user_func($callback, $args);
	}

	/**
	 * Check whether allowed access based on a set of roles using the authorization_callback option.
	 *
	 * @param array|string $params
	 * @param string       $name
	 *
	 * @return bool
	 *
	 * @since 2.0.0
	 */
	public function isAllowed($params, $name = null)
	{
		$callback = $this->getOption('authorization_callback');
		$allowed = true;
		if ($callback !== null)
		{
			$allowed = call_user_func($callback, $params, $name);
		}
		return $allowed;
	}
	

	/**
	 * Render carousel.
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	public function renderCarousel()
	{
		$carousel = $this->getOption('carousel');
		if ($this->getOption('carousel') && !empty($carousel['content']) && $this->getOption('show_carousel'))
		{
			$images = "";
			$indicators = "";
			$index = 0;
			foreach ($carousel['content'] as $image)
			{
				$div = ($index==0) ? '<div class="item active">' : "\n\t\t" . '<div class="item">';
				if ($image['url'])
				{
					$div .= "\n\t\t\t" . '<a href="' . $image['url'] . '">';
				}
				$div .= "\n\t\t\t\t<img src=\"$image[src]\" alt=\""
					. ($image['alt'] ?: ($image['description'] ?: $image['title']))
					. "\">\n";
				if ($carousel['show_captions'] && ($image['title'] || $image['description']))
				{
					$div .= <<<HTML

				<div class="carousel-caption">
					<div class="carousel-caption-title">{$image['title']}</div>
					<div class="carousel-caption-description">{$image['description']}</div>
				</div>
HTML;
				}
				if ($image['url'])
				{
					$div .= "\n\t\t\t" . '</a>';
				}
				$div .= "\n\t\t" . '</div>';
				$images .= $div;
				$activeIndicator = $index==0?'active':'';
				$ariaSelected = $index==0?'true':'false';
				$indexPlusOne = $index+1;
				$indicators .= <<<HTML

				<li class="btn btn-carousel {$activeIndicator}" data-target="#slideshow" data-slide-to="{$index}" role="tab" id="tab-0-{$index}" aria-controls="tabpanel-0-{$index}" aria-selected="{$ariaSelected}" title="{$image['description']}" tabindex="0">{$indexPlusOne}<span class="sr-only">{$image['description']}</span></li>
HTML;
				$index++;
			}
			if ($carousel['show_side_buttons'])
			{
				$sideNavs = <<<HTML
	<div class="control-btn">
		<a href="#slideshow" class="left carousel-control" role="button" data-slide="prev">
			<span class="fa fa-chevron-left" aria-hidden="true"><span class="sr-only">Previous Image</span></span>
		</a>
		<a href="#slideshow" class="right carousel-control" role="button" data-slide="next">
			<span class="fa fa-chevron-right" aria-hidden="true"><span class="sr-only">Next Image</span></span>
		</a>
	</div>
HTML;
			}
			else
			{
				$sideNavs = "";
			}
			return <<<HTML
	<div class="{$this->getContainer()}">

<div id="slideshow" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner" role="listbox">
		{$images}
	</div>
	{$sideNavs}
	<div class="carousel-controls">
		<div class="carousel-player">
			<button id="playButton" class="carousel-play btn btn-carousel" type="button" aria-label="Play Slideshow">
				<span id="playSpan" class="fa fa-play"></span>
			</button>
		</div>
		<ol class="carousel-indicators" role="tablist">{$indicators}
		</ol>
	</div>
</div>

	</div>
HTML;
		}
		return null;
	}

	/**
	 * Render right navigation.
	 *
	 * @since 2.0.0
	 */
	public function renderRightNav()
	{
		$targets = $this->getOption('right_nav');
		if ($targets === null || empty($targets))
		{
			return;
		}
		$attrs = array(
			'class' => 'right-nav hidden-sm hidden-xs',
		);
		if ($this->getOption('right_nav_scroll_spy') === true)
		{
			$attrs['id'] = 'right-nav-scroll-spy';
		}
		if ($this->getOption('right_nav_collapse') === true)
		{
			$attrs['class'] .= ' right-nav-collapse';
		}
		if ($this->getOption('right_nav_affix') === true || $this->getOption('right_nav_scroll_spy') === true)
		{
			$attrs['data-spy'] = 'affix';
			if ($this->getOption('navbar_menu_affix'))
			{
				$attrs['class'] .= ' nav-menu-affixed';
			}
		}
		$rightNav = <<<HTML
<nav {$this->createAttributesString($attrs)}>
	{$this->renderScrollTargetList($targets)}
</nav>
HTML;
		$this->setOption('right_sidebar', $rightNav . $this->getOption('right_sidebar'));
	}

	/**
	 * @param $targets
	 *
	 * @return string
	 *
	 * @since 2.0.0
	 */
	private function renderScrollTargetList($targets)
	{
		$targetList = array();
		foreach ($targets as $target)
		{
			$id= $target['id'];
			$label= $target['label'];
			$subTargetList = isset($target['sub_targets']) ? $this->renderScrollTargetList($target['sub_targets']) : '';
			$class = $this->getOption('navbar_menu_affix') ? ' class="target-offset"' : '';
			$targetList[] = <<<HTML
<li>
	<a href="#{$id}"{$class}>{$label}</a>
	{$subTargetList}
</li>
HTML;
		}
		$targetList = implode("\n", $targetList);
		$attrs = array(
			'class' => 'nav',
		);
		return <<<HTML
<ul {$this->createAttributesString($attrs)}>
	{$targetList}
</ul>
HTML;
	}

	/**
	 * @param $megaMenuContent string
	 *
	 * @return string
	 *
	 * @since 2.0.2
	 */
	public function renderMegaMenu($megaMenuContent)
	{
		return <<<HTML
		<div class="dropdown-menu {$this->getContainer()}" role="menu">$megaMenuContent</div>
HTML;
	}
}
