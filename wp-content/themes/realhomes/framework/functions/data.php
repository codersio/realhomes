<?php
/**
 * This file contains some reusable classes for theme's code
 *
 * @package realhomes/functions
 */

class RH_Data {

	// Array of pages with page id as index and title as value
	private static array $pages_array;
	private static array $posts_array;

	/**
	 * Returns an array of pages with page id as index and title as value
	 *
	 * @return Array of pages with page id as index and title as value
	 */
	public static function get_pages_array(): array {
		if ( empty( self::$pages_array ) ) {
			$pages_objects     = get_pages();
			self::$pages_array = array( 0 => esc_html__( 'None', 'framework' ) );
			if ( 0 < count( $pages_objects ) ) {
				foreach ( $pages_objects as $single_page ) {
					self::$pages_array[ $single_page->ID ] = $single_page->post_title;
				}
			}
		}
		return self::$pages_array;
	}

	/**
	 * Returns an array of post/cpt with id as index and title as value
	 *
	 * @since 4.2.0
	 *
	 * @param $post_type string
	 *
	 * @return Array of posts with IDs as keys
	 */
	public static function get_posts_array( $post_type ) {
		if ( empty( self::$posts_array ) ) {
			$posts_objects     = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => -1 ) );
			self::$posts_array = array( 0 => esc_html__( 'None', 'framework' ) );
			if ( 0 < count( $posts_objects ) ) {
				foreach ( $posts_objects as $single_post ) {
					self::$posts_array[ $single_post->ID ] = $single_post->post_title;
				}
			}
		}
		return self::$posts_array;
	}

}