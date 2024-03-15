<?php
/**
 * Custom Header Footer Class
 *
 * @since 0.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RHEA_Elementor_Header_Footer {


	public function __construct() {

		add_action( 'realhomes_elementor_header_content', array( $this, 'rhea_header' ), 10, 1 );
		add_action( 'realhomes_elementor_footer_content', array( $this, 'rhea_footer' ), 30, 1 );
	}

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}
	/**
	 * Generate Header/Footer Contents
	 *
	 * @since 0.9.7
	 */
	public function rhea_elementor_contents( $id, $attributes = [] ) {

		$rhea_include_css = false;

		if ( isset( $attributes['css'] ) && 'false' !== $attributes['css'] ) {
			$rhea_include_css = (bool) $attributes['css'];
		}

		echo self::elementor()->frontend->get_builder_content_for_display( $id, $rhea_include_css );
	}
	/**
	 * Display Custom Header
	 *
	 * @since 0.9.7
	 */
	public function rhea_header() {

		$custom_header_for_post = get_post_meta(get_queried_object_id(),'REAL_HOMES_custom_header_display',true);
		$realhomes_header_options_array = get_option( 'realhomes_custom_header', 'default' );
		if(!empty($custom_header_for_post) && 'default' !==  $custom_header_for_post){
			self::rhea_elementor_contents( $custom_header_for_post );
		}elseif ( ( $realhomes_header_options_array ) && 'default' !== $realhomes_header_options_array ) {
			self::rhea_elementor_contents( $realhomes_header_options_array );
		}else{
			return;
		}

	}
	/**
	 * Display Custom Footer
	 *
	 * @since 0.9.7
	 */
	public function rhea_footer() {

		$custom_footer_for_post = get_post_meta(get_queried_object_id(),'REAL_HOMES_custom_footer_display',true);
		$realhomes_custom_footer_is_selected = get_option( 'realhomes_custom_footer_is_selected', 'default' );

		if(!empty($custom_footer_for_post) && 'default' !==  $custom_footer_for_post){
			self::rhea_elementor_contents( $custom_footer_for_post );
		}elseif ( $realhomes_custom_footer_is_selected && 'default' !== $realhomes_custom_footer_is_selected ) {
			self::rhea_elementor_contents( $realhomes_custom_footer_is_selected );
		}else{
			return;
		}

	}
}

new RHEA_Elementor_Header_Footer();
