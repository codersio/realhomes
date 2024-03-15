<?php
/**
 * Custom Single Property Class
 *
 * @since 2.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RHEA_Elementor_Single_Property {


	public function __construct() {

		add_action( 'realhomes_elementor_single_property_content', array( $this, 'rhea_single_property' ), 10, 1 );
	}

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * Generate Single Property Contents
	 * Used to render and return the post content with all the Elementor elements.
	 *
	 * @since 4.0.1
	 */
	public function rhea_elementor_contents( $id, $attributes = [] ) {

		$rhea_include_css = false;

		if ( isset( $attributes['css'] ) && 'false' !== $attributes['css'] ) {
			$rhea_include_css = (bool)$attributes['css'];
		}

		echo self::elementor()->frontend->get_builder_content_for_display( $id, $rhea_include_css );
	}

	/**
	 * Display Custom Single Property At Frontend
	 *
	 * @since 4.0.1
	 */
	public function rhea_single_property() {

		$custom_single_property_template_meta   = get_post_meta( get_queried_object_id(), 'realhomes_elementor_single_property_display', true );
		$custom_single_property_template_option = get_option( 'realhomes_elementor_property_single_template', 'default' );
		if ( ! empty( $custom_single_property_template_meta ) && 'default' !== $custom_single_property_template_meta ) {
			self::rhea_elementor_contents( $custom_single_property_template_meta );
		} else if ( ( $custom_single_property_template_option ) && 'default' !== $custom_single_property_template_option ) {
			self::rhea_elementor_contents( $custom_single_property_template_option );
		} else {
			return;
		}

	}
}

new RHEA_Elementor_Single_Property();
