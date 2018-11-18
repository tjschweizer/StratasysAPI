<?php
/**
 * How to integrate WordPress Core updates with your custom Plugin or Theme
 *
 * Filter the `update_plugins` transient to report your plugin as out of date.
 * Themes have a similar transient you can filter.
 */
add_filter( 'site_transient_update_themes', 'wprp_extend_filter_update_plugins' );
add_filter( 'transient_update_themes', 'wprp_extend_filter_update_plugins' );
function wprp_extend_filter_update_plugins( $update_themes ) {
	if ( defined( 'DISALLOW_FILE_MODS' ) && true == DISALLOW_FILE_MODS ) {
		return $update_themes;
	}

	if ( isset($update_themes->checked['iastate-theme'])) {

		$theme_version = $update_themes->checked['iastate-theme'];

		if ( isset ( $update_themes->response['iastate-theme'] ) ) {
			unset( $update_themes->response['iastate-theme'] );
		}

		$url = 'https://sites.engineering.iastate.edu/IastateTheme/update';

		$request  = wp_remote_get( $url );
		$response = wp_remote_retrieve_body( $request );
		$release  = json_decode( $response );

		if ( $theme_version !== $release->version ) {
			$update_themes->response['iastate-theme'] = array(
				'theme'       => 'iastate-theme',
				'new_version' => $release->version,
				'url'         => 'https://sites.engineering.iastate.edu/IastateTheme/changelog/' . $release->version,
				'package'     => $release->download,
			);
		}
	}

	return $update_themes;
}