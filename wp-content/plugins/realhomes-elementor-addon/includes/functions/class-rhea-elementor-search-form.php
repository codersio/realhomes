<?php
/**
 * Custom Search Form Class
 *
 * @since 0.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RHEA_Elementor_Search_Form {


	public function __construct() {
		add_action( 'realhomes_elementor_search_form', array( $this, 'rhea_search_form' ), 10, 1 );
	}

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * Generate Search Form Contents
	 */
	public function elementor_contents( $id, $attributes = [] ) {

		$rhea_include_css = false;

		if ( isset( $attributes['css'] ) && 'false' !== $attributes['css'] ) {
			$rhea_include_css = (bool)$attributes['css'];
		}

		echo self::elementor()->frontend->get_builder_content_for_display( $id, $rhea_include_css );
	}

	/**
	 * Display Custom Search Form
	 */
	public function rhea_search_form( $args = '' ) {
		$REAL_HOMES_custom_search_form = get_post_meta( get_queried_object_id(), 'REAL_HOMES_custom_search_form', true );
		$realhomes_search_form_option  = get_option( 'realhomes_custom_search_form', 'default' );

		if ( ! empty( $args ) ) {
			self::elementor_contents( $args );
		} else if ( ! empty( $REAL_HOMES_custom_search_form ) && 'default' !== $REAL_HOMES_custom_search_form ) {
			self::elementor_contents( $REAL_HOMES_custom_search_form );
		} else if ( ( $realhomes_search_form_option ) && 'default' !== $realhomes_search_form_option  && realhomes_custom_search_form_available()  ) {
				self::elementor_contents( $realhomes_search_form_option );
		} else {
			return;
		}

	}
}

new RHEA_Elementor_Search_Form();
