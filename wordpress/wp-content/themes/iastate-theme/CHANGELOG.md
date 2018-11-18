# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased] - _Upcoming changes_

### Changed
- Empty footer hidden on small and extra-small screens

### Removed
- Footer options removed from customizer

## [2.0.16] - _2018-04-17_

### Fixed
- Front page template breaking some child theme setups

## [2.0.15] - _2018-04-17_

### Added
- Homepage widget areas styled like iastate.edu homepage
- Footer widgets
- front_page.php file to more easily manage static front pages

## [2.0.14] - _2018-04-02_

### Changed
- fixed post date. post published date/time to use local WordPress formatting

## [2.0.13] - _2018-03-30_

### Changed
- post single and list layout updates
- Updated PHP theme to version 2.0.12
- h1 titles are hidden instead of removed

## Added
- pagination style

## [2.0.12] - _2018-01-23_

### Changed
- Default setter for PHP theme's `site_url` option changed from site_url() to home_url() after looking at WP 
documentation for the function's intended use. 

## [2.0.11] - _2018-01-08_

### Changed
- Updated PHP theme to version 2.0.8

## [2.0.10] - _2017-12-07_

### Changed
- Updated PHP theme to version 2.0.7

## [2.0.9] - _2017-11-09_

### Added
- Mega Menus

### Changed
- Updated PHP theme to version 2.0.6

## [2.0.8] - _2017-11-08_

### Changed
- Added additional menu features

## [2.0.7] - _2017-11-07_

### Changed
- Updated PHP theme to stable version 2.0.5

## [2.0.0] - _2017-06-09_

### Changed
- Updated PHP theme to stable version 2.0.1
- Modified WordPress version number to semantically be in sync with the first 2 digits of the PHP theme

## [0.5.0] - _2017-05-18_

### Added
- Address Url in customizer
- Carousel shortcode
- `navs` and `post_nav` theme options can be added through post meta (runs shortcodes)

### Changed
- Updated IASTATE Theme PHP Class to Beta 3.3

## [0.4.0] - _2017-05-10_

### Added
- Ability to update through the WordPress updater

## [0.3.2] - _2017-05-01_

### Changed
- Customizer options for some footer theme modifications

## [0.3.1] - _2017-04-24_

### Changed
- tags in git should now match Changelog

### Added
- Customizer theme options
- added some fixer scripts to help convert src files to work with WordPress

## [0.3.0] - _2017-02-15_

### Changed
- Use Iastate Theme class to render most theme elements

### Added
- PHP IastateTheme Vendor Library
- Some filters and actions:
    - iastate_theme_header
    - iastate_theme_footer
    - iastate_register_defaults

## [0.2.0] - _2017-01-06_

### Changed
- IASTATE Theme Beta 2 content

## [0.1.0] - _2017-01-03_

### Added
- Changelog

[Unreleased]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.16&sourceBranch=refs%2Fheads%2Fdev&targetRepoId=1552
[2.0.15]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.15&sourceBranch=refs%2Ftags%2F2.0.16&targetRepoId=1552
[2.0.15]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.14&sourceBranch=refs%2Ftags%2F2.0.15&targetRepoId=1552
[2.0.14]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.13&sourceBranch=refs%2Ftags%2F2.0.14&targetRepoId=1552
[2.0.13]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.12&sourceBranch=refs%2Ftags%2F2.0.13&targetRepoId=1552
[2.0.12]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.11&sourceBranch=refs%2Ftags%2F2.0.12&targetRepoId=1552
[2.0.11]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.10&sourceBranch=refs%2Ftags%2F2.0.11&targetRepoId=1552
[2.0.10]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.9&sourceBranch=refs%2Ftags%2F2.0.10&targetRepoId=1552
[2.0.9]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.8&sourceBranch=refs%2Ftags%2F2.0.9&targetRepoId=1552
[2.0.8]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.7&sourceBranch=refs%2Ftags%2F2.0.8&targetRepoId=1552
[2.0.7]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F2.0.0&sourceBranch=refs%2Ftags%2F2.0.7&targetRepoId=1552
[2.0.0]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F0.5.0&sourceBranch=refs%2Ftags%2F2.0.0&targetRepoId=1552
[0.5.0]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F0.4.0&sourceBranch=refs%2Ftags%2F0.5.0&targetRepoId=1552
[0.4.0]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F0.3.2&sourceBranch=refs%2Ftags%2F0.4.0&targetRepoId=1552
[0.3.2]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F0.3.1&sourceBranch=refs%2Ftags%2F0.3.2&targetRepoId=1552
[0.3.1]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F0.2.4&sourceBranch=refs%2Ftags%2F0.3.1&targetRepoId=1552
[0.3.0]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/compare/diff?targetBranch=refs%2Ftags%2F0.2.0&sourceBranch=refs%2Ftags%2F0.2.4&targetRepoId=1552
[0.2.0]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/commits/1673ce6addc7d9a5c641d2848ed1514aa589b973
[0.1.0]: https://git.its.iastate.edu/projects/IASTATE-THEME/repos/wordpress/commits/d5a2c074a385b149047d5e6258adbff7713ea9c9