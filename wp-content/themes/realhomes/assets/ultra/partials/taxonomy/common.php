<?php
/**
 * Common template for taxonomy archives.
 *
 * @since      4.0.0
 * @package    realhomes
 * @subpackage ultra
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
	/*
	 * View type as grid layout can also have buttons to display list layout.
	 */

	$view_type = 'grid';
	if ( isset( $_GET['view'] ) ) {
		$view_type = $_GET['view'];
	} else {
		/* Theme Options Listing Layout */
		$view_type = get_option( 'theme_listing_layout' );
	}

	if ( 'list' === $view_type ) {
		get_template_part( 'assets/ultra/partials/properties/archive/list' );
	} else {
		get_template_part( 'assets/ultra/partials/properties/archive/grid' );
	}

}
get_footer();
