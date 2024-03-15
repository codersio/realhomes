<?php
/**
 * Contains Basic Functions for RealHomes Elementor Addon plugin.
 */

/**
 * Get template part for RHEA plugin.
 *
 * @access public
 *
 * @param mixed  $slug Template slug.
 * @param string $name Template name (default: '').
 */
function rhea_get_template_part( $slug, $name = '' ) {
	$template = '';

	// Get slug-name.php.
	if ( ! $template && $name && file_exists( RHEA_PLUGIN_DIR . "/{$slug}-{$name}.php" ) ) {
		$template = RHEA_PLUGIN_DIR . "/{$slug}-{$name}.php";
	}

	// Get slug.php.
	if ( ! $template && file_exists( RHEA_PLUGIN_DIR . "/{$slug}.php" ) ) {
		$template = RHEA_PLUGIN_DIR . "/{$slug}.php";
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'rhea_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

if ( ! function_exists( 'rhea_allowed_html' ) ) {
	/**
	 * Returns array of allowed tags to be used in wp_kses function
     *
     * @modified 2.1.1
	 *
	 * @return array
	 */
	function rhea_allowed_html() {

		$allowed_html = array(
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'alt'    => array(),
				'target' => array(),
			),
			'b'      => array(),
			'br'     => array(),
			'div'    => array(
				'class' => array(),
				'id'    => array(),
			),
			'em'     => array(),
			'strong' => array(),
			'i'      => array(
				'aria-hidden' => array(),
				'class'       => array()
			)
		);

		return apply_filters( 'rhea_allowed_html', $allowed_html );
	}
}


if ( ! function_exists( 'rhea_list_gallery_images' ) ) {
	/**
	 * Get list of Gallery Images - use in gallery post format
	 *
	 * @param string $size
	 */
	function rhea_list_gallery_images( $size = 'post-featured-image' ) {

		$gallery_images = rwmb_meta( 'REAL_HOMES_gallery', 'type=plupload_image&size=' . $size, get_the_ID() );

		if ( ! empty( $gallery_images ) ) {
			foreach ( $gallery_images as $gallery_image ) {
				$caption = ( ! empty( $gallery_image['caption'] ) ) ? $gallery_image['caption'] : $gallery_image['alt'];
				echo '<li><a href="' . esc_url( $gallery_image['full_url'] ) . '" title="' . esc_attr( $caption ) . '" data-fancybox="thumbnail-' . get_the_ID() . '">';
				echo '<img src="' . esc_url( $gallery_image['url'] ) . '" alt="' . esc_attr( $gallery_image['title'] ) . '" />';
				echo '</a></li>';
			}
		} else if ( has_post_thumbnail( get_the_ID() ) ) {
			echo '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '" >';
			the_post_thumbnail( $size );
			echo '</a></li>';
		}
	}
}


if ( ! function_exists( 'rhea_framework_excerpt' ) ) {
	/**
	 * Output custom excerpt of required length
	 *
	 * @param int    $len  number of words
	 * @param string $trim string to appear after excerpt
	 */
	function rhea_framework_excerpt( $len = 15, $trim = "&hellip;" ) {
		echo rhea_get_framework_excerpt( $len, $trim );
	}
}


if ( ! function_exists( 'rhea_get_framework_excerpt' ) ) {
	/**
	 * Returns custom excerpt of required length
	 *
	 * @param int    $len  number of words
	 * @param string $trim string after excerpt
	 *
	 * @return string
	 */
	function rhea_get_framework_excerpt( $len = 15, $trim = "&hellip;" ) {
		$limit     = $len + 1;
		$excerpt   = explode( ' ', get_the_excerpt(), $limit );
		$num_words = count( $excerpt );
		if ( $num_words >= $len ) {
			array_pop( $excerpt );
		} else {
			$trim = "";
		}
		$excerpt = implode( " ", $excerpt ) . "$trim";

		return $excerpt;
	}
}

if ( ! function_exists( 'rhea_get_framework_excerpt_by_id' ) ) {
	/**
	 * Returns custom excerpt of required length
	 *
	 * @param int    $id   post ID
	 * @param int    $len  number of words
	 * @param string $trim string after excerpt
	 *
	 * @return string
	 */
	function rhea_get_framework_excerpt_by_id( $id, $len = 15, $trim = "&hellip;" ) {
		$limit     = $len + 1;
		$excerpt   = explode( ' ', get_the_excerpt( $id ), $limit );
		$num_words = count( $excerpt );
		if ( $num_words >= $len ) {
			array_pop( $excerpt );
		} else {
			$trim = "";
		}
		$excerpt = implode( " ", $excerpt ) . "$trim";

		return $excerpt;
	}
}


if ( ! function_exists( 'RHEA_ajax_pagination' ) ) {
	/**
	 * Function for Widgets AJAX pagination
	 *
	 * @param string $pages
	 * @param string $container_class
	 */
	function RHEA_ajax_pagination( $pages = '', $container_class = '' ) {

		global $wp_query;

		$paged = 1;
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) { // if is static front page
			$paged = get_query_var( 'page' );
		}

		$prev          = $paged - 1;
		$next          = $paged + 1;
		$range         = 3;                             // change it to show more links
		$pages_to_show = ( $range * 2 ) + 1;

		if ( $pages == '' ) {
			$pages = $wp_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}

		if ( empty( $container_class ) ) {
			$container_class = 'rhea_pagination_wrapper';
		}

		if ( 1 != $pages ) {
			printf( '<div class="%s">', esc_attr( $container_class ) );
			echo "<div class='pagination rhea-pagination-clean'>";

			if ( ( $paged > 2 ) && ( $paged > $range + 1 ) && ( $pages_to_show < $pages ) ) {
				echo "<a href='" . get_pagenum_link( 1 ) . "' class='real-btn'> " . esc_html__( 'First', 'realhomes-elementor-addon' ) . "</a> "; // First Page
			}

			if ( ( $paged > 1 ) && ( $pages_to_show < $pages ) ) {
				echo "<a href='" . get_pagenum_link( $prev ) . "' class='real-btn'> " . esc_html__( 'Prev', 'realhomes-elementor-addon' ) . "</a> "; // Previous Page
			}

			$min_page_number = $paged - $range - 1;
			$max_page_number = $paged + $range + 1;

			for ( $i = 1; $i <= $pages; $i++ ) {
				if ( ( ( $i > $min_page_number ) && ( $i < $max_page_number ) ) || ( $pages <= $pages_to_show ) ) {
					$current_class = 'real-btn';
					$current_class .= ( $paged == $i ) ? ' current' : '';
					echo "<a href='" . get_pagenum_link( $i ) . "' class='" . $current_class . "' >" . $i . "</a> ";
				}
			}

			if ( ( $paged < $pages ) && ( $pages_to_show < $pages ) ) {
				echo "<a href='" . get_pagenum_link( $next ) . "' class='real-btn'>" . esc_html__( 'Next', 'realhomes-elementor-addon' ) . " </a> "; // Next Page
			}

			if ( ( $paged < $pages - 1 ) && ( $paged + $range - 1 < $pages ) && ( $pages_to_show < $pages ) ) {
				echo "<a href='" . get_pagenum_link( $pages ) . "' class='real-btn'>" . esc_html__( 'Last', 'realhomes-elementor-addon' ) . " </a> "; // Last Page
			}

			echo "</div>";
			echo "</div>";
		}
	}
}

if ( ! function_exists( 'RHEA_ultra_ajax_pagination' ) ) {
	/**
	 * Function for Widgets AJAX pagination
	 *
	 * @param string $pages
	 */
	function RHEA_ultra_ajax_pagination( $pages = '' ) {

		global $wp_query;

		$paged = 1;
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) { // if is static front page
			$paged = get_query_var( 'page' );
		}

		$prev          = $paged - 1;
		$next          = $paged + 1;
		$range         = 3;                             // change it to show more links
		$pages_to_show = ( $range * 2 ) + 1;

		if ( $pages == '' ) {
			$pages = $wp_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}

		if ( 1 != $pages ) {
			echo "<div class='rhea_ultra_pagination_wrapper rhea_pagination_wrapper'>";
			echo "<div class='pagination rhea-pagination-clean'>";

			if ( ( $paged > 2 ) && ( $paged > $range + 1 ) && ( $pages_to_show < $pages ) ) {
				echo "<a href='" . get_pagenum_link( 1 ) . "' class='rhea-ultra-btn real-btn'><i class='fas fa-caret-left'></i><i class='fas fa-caret-left'></i></a> "; // First Page
			}

			if ( ( $paged > 1 ) && ( $pages_to_show < $pages ) ) {
				echo "<a href='" . get_pagenum_link( $prev ) . "' class='rhea-ultra-btn real-btn'><i class='fas fa-caret-left'></i></a> "; // Previous Page
			}

			$min_page_number = $paged - $range - 1;
			$max_page_number = $paged + $range + 1;

			?>
            <div class="rhea_ultra_pagination_counter">
                <div class="rhea_ultra_pagination_counter_inner">
					<?php
					for ( $i = 1; $i <= $pages; $i++ ) {
						if ( ( ( $i > $min_page_number ) && ( $i < $max_page_number ) ) || ( $pages <= $pages_to_show ) ) {
							$current_class = 'real-btn';
							$current_class .= ( $paged == $i ) ? ' current' : '';
							echo "<a href='" . get_pagenum_link( $i ) . "' class='" . $current_class . "' >" . $i . "</a> ";
						}
					}
					?>
                </div>
            </div>
			<?php

			if ( ( $paged < $pages ) && ( $pages_to_show < $pages ) ) {
				echo "<a href='" . get_pagenum_link( $next ) . "' class='rhea-ultra-btn real-btn'><i class='fas fa-caret-right'></i> </a> "; // Next Page
			}

			if ( ( $paged < $pages - 1 ) && ( $paged + $range - 1 < $pages ) && ( $pages_to_show < $pages ) ) {
				echo "<a href='" . get_pagenum_link( $pages ) . "' class='rhea-ultra-btn real-btn'><i class='fas fa-caret-right'></i><i class='fas fa-caret-right'></i></a> "; // Last Page
			}

			echo "</div>";
			echo "</div>";
		}
	}
}

if ( ! function_exists( 'rhea_property_price' ) ) {
	/**
	 * Output property price
	 */
	function rhea_property_price() {
		echo rhea_get_property_price();
	}
}

if ( ! function_exists( 'rhea_get_property_price' ) ) {
	/**
	 * Returns property price in configured format
	 *
	 * @return mixed|string
	 */
	function rhea_get_property_price() {

		// get property price
		$price_digits = doubleval( get_post_meta( get_the_ID(), 'REAL_HOMES_property_price', true ) );

		if ( $price_digits ) {
			// get price prefix and postfix
			$price_pre_fix  = get_post_meta( get_the_ID(), 'REAL_HOMES_property_price_prefix', true );
			$price_post_fix = get_post_meta( get_the_ID(), 'REAL_HOMES_property_price_postfix', true );

			// if wp-currencies plugin installed and current currency cookie is set
			if ( class_exists( 'WP_Currencies' ) && isset( $_COOKIE["current_currency"] ) ) {
				$current_currency = $_COOKIE["current_currency"];
				if ( currency_exists( $current_currency ) ) {    // validate current currency
					$base_currency             = ere_get_base_currency();
					$converted_price           = convert_currency( $price_digits, $base_currency, $current_currency );
					$formatted_converted_price = format_currency( $converted_price, $current_currency );
					$formatted_converted_price = apply_filters( 'inspiry_property_converted_price', $formatted_converted_price, $price_digits );

					return $price_pre_fix . ' ' . $formatted_converted_price . ' ' . $price_post_fix;
				}
			}

			// otherwise go with default approach.
			$currency            = ere_get_currency_sign();
			$decimals            = intval( get_option( 'theme_decimals', '0' ) );
			$decimal_point       = get_option( 'theme_dec_point', '.' );
			$thousands_separator = get_option( 'theme_thousands_sep', ',' );
			$currency_position   = get_option( 'theme_currency_position', 'before' );
			$formatted_price     = number_format( $price_digits, $decimals, $decimal_point, $thousands_separator );
			$formatted_price     = apply_filters( 'inspiry_property_price', $formatted_price, $price_digits );

			if ( 'after' === $currency_position ) {
				return $price_pre_fix . ' ' . $formatted_price . $currency . ' <span>' . $price_post_fix . '</span>';
			} else {
				return $price_pre_fix . ' ' . $currency . $formatted_price . ' <span>' . $price_post_fix . '</span>';
			}

		} else {
			return ere_no_price_text();
		}
	}
}

if ( ! function_exists( 'rhea_display_property_label' ) ) {
	/**
	 * Display property label
	 *
	 * @param int $post_id
	 * @param string $class
	 */
	function rhea_display_property_label( $post_id, $class = 'rhea-property-label' ) {

		$label_text = get_post_meta( $post_id, 'inspiry_property_label', true );
		$color      = get_post_meta( $post_id, 'inspiry_property_label_color', true );
		if ( ! empty ( $label_text ) ) {
			?>
            <span style="background: <?php echo esc_attr( $color ); ?>; border-color: <?php echo esc_attr( $color ); ?>" class='<?php echo esc_attr( $class ) ?>'><?php echo esc_html( $label_text ); ?></span>
			<?php

		}
	}
}

if ( ! function_exists( 'rhea_get_maps_type' ) ) {
	/**
	 * Returns the type currently available for use.
	 */
	function rhea_get_maps_type() {
		$google_maps_api_key = get_option( 'inspiry_google_maps_api_key', false );

		if ( ! empty( $google_maps_api_key ) ) {
			return 'google-maps';    // For Google Maps
		}

		return 'open-street-map';    // For OpenStreetMap https://www.openstreetmap.org/
	}
}

if ( ! function_exists( 'rhea_switch_currency_plain' ) ) {
	/**
	 * Convert and format given amount from base currency to current currency.
	 *
	 * @since  1.0.0
	 *
	 * @param string $amount Amount in digits to change currency for.
	 *
	 * @return string
	 */
	function rhea_switch_currency_plain( $amount ) {

		if ( function_exists( 'realhomes_currency_switcher_enabled' ) && realhomes_currency_switcher_enabled() ) {
			$base_currency    = realhomes_get_base_currency();
			$current_currency = realhomes_get_current_currency();
			$converted_amount = realhomes_convert_currency( $amount, $base_currency, $current_currency );

			return apply_filters( 'realhomes_switch_currency', $converted_amount );
		}
	}
}


if ( ! function_exists( 'rhea_get_location_options' ) ) {

	/**
	 * Return Property Locations as Options List in Json format
	 */
	function rhea_get_location_options() {


		$options         = array(); // A list of location options will be passed to this array
		$number          = 15; // Number of locations that will be returned per Ajax request
		$locations_order = array(
			'orderby' => 'count',
			'order'   => 'desc',
		);

		$offset = '';
		if ( isset( $_GET['page'] ) ) {
			$offset = $number * ( $_GET['page'] - 1 ); // Offset of locations list for the current Ajax request
		}

		if ( isset( $_GET['sortplpha'] ) && 'yes' == $_GET['sortplpha'] ) {
			$locations_order['orderby'] = 'name';
			$locations_order['order']   = 'asc';
		}


		if ( isset( $_GET['hideemptyfields'] ) && 'yes' == $_GET['hideemptyfields'] ) {
			$hide_empty_location = true;
		} else {
			$hide_empty_location = false;
		}


		// Prepare a query to fetch property locations from database
		$terms_query = array(
			'taxonomy'   => 'property-city',
			'number'     => $number,
			'offset'     => $offset,
			'hide_empty' => $hide_empty_location,
			'orderby'    => $locations_order['orderby'],
			'order'      => $locations_order['order'],
		);

		// If there is a search parameter
		if ( isset( $_GET['query'] ) ) {
			$terms_query['name__like'] = $_GET['query'];
		}

		$locations = get_terms( $terms_query );

		// Build an array of locations info form their objects
		if ( ! empty( $locations ) && ! is_wp_error( $locations ) ) {
			foreach ( $locations as $location ) {
				$options[] = array( $location->slug, $location->name );
			}
		}

		echo json_encode( $options ); // Return locations list in Json format
		die;
	}

	add_action( 'wp_ajax_rhea_get_location_options', 'rhea_get_location_options' );
	add_action( 'wp_ajax_nopriv_rhea_get_location_options', 'rhea_get_location_options' );

}

if ( ! function_exists( 'rhea_searched_ajax_locations' ) ) {
	/**
	 * Display Property Ajax Searched Locations Select Options
	 */

	function rhea_searched_ajax_locations() {

		$searched_locations = '';
		if ( isset( $_GET['location'] ) ) {
			$searched_locations = $_GET['location'];
		}

		if ( is_array( $searched_locations ) && ! empty( $_GET['location'] ) ) {

			foreach ( $searched_locations as $location ) {
				$searched_terms = get_term_by( 'slug', $location, 'property-city' );
				?>
                <option value="<?php echo esc_attr( $searched_terms->slug ) ?>" selected="selected"><?php echo esc_html( $searched_terms->name ) ?></option>
				<?php
			}
		} else if ( ! empty( $searched_terms ) ) {
			$searched_terms = get_term_by( 'slug', $searched_locations, 'property-city' );
			?>
            <option value="<?php echo esc_attr( $searched_terms->slug ) ?>" selected="selected"><?php echo esc_html( $searched_terms->name ) ?></option>
			<?php
		}

	}
}

if ( ! function_exists( 'rhea_rvr_rating_average' ) ) {
	/**
	 * Display rating average based on approved comments with rating
	 */
	function rhea_rvr_rating_average() {

		$args = array(
			'post_id' => get_the_ID(),
			'status'  => 'approve',
		);

		$comments = get_comments( $args );
		$ratings  = array();
		$count    = 0;

		foreach ( $comments as $comment ) {

			$rating = get_comment_meta( $comment->comment_ID, 'inspiry_rating', true );

			if ( ! empty( $rating ) ) {
				$ratings[] = absint( $rating );
				$count++;
			}
		}


		$allowed_html = array(
			'span' => array(
				'class' => array(),
			),
			'i'    => array(
				'class' => array(),
			),
		);

		if ( 0 !== count( $ratings ) ) {

			$values_count = ( array_count_values( $ratings ) );


			$avg = round( array_sum( $ratings ) / count( $ratings ), 2 );
			?>
            <div class="rhea_rvr_ratings">
                <div class="rhea_stars_avg_rating" title="<?php echo esc_html( $avg ) . ' / ' . esc_html__( '5 based on', 'realhomes-elementor-addon' ) . ' ' . esc_html( $count ) . ' ' . esc_html__( 'reviews', 'realhomes-elementor-addon' );
				?>">
					<?php
					echo wp_kses( rhea_rating_stars( $avg ), $allowed_html );
					?>

                    <div class="rhea_wrapper_rating_info">

						<?php


						$i = 5;
						while ( $i > 0 ) {
							?>
                            <p class="rhea_rating_percentage">
                            <span class="rhea_rating_sorting_label">
                                <?php
                                printf( _nx( '%s Star', '%s Stars', $i, 'Rating Stars', 'realhomes-elementor-addon' ), number_format_i18n( $i ) );
                                ?>
                            </span>
								<?php
								if ( isset( $values_count[ $i ] ) && ! empty( $values_count[ $i ] ) ) {
									$stars = round( ( $values_count[ $i ] / ( count( $ratings ) ) ) * 100 );
								} else {
									$stars = 0;
								}
								?>

                                <span class="rhea_rating_line">
                                <span class="rhea_rating_line_inner" style="width: <?php echo esc_attr( $stars ); ?>%"></span>
                            </span>

                                <span class="rhea_rating_text">
                            <span class="rhea_rating_text_inner">

                                <?php echo esc_html( $stars ) . '%' ?>
                            </span>
                            </span>
                            </p>
							<?php

							$i--;
						}
						?>


                    </div>
                </div>
            </div>
			<?php

		}
	}
}

if ( ! function_exists( 'rhea_rating_stars' ) ) {
	/**
	 * Display rated stars based on given number of rating
	 *
	 * @param int $rating - Average rating.
	 *
	 * @return string
	 */
	function rhea_rating_stars( $rating ) {

		$output = '';

		if ( ! empty( $rating ) ) {

			$whole    = floor( $rating );
			$fraction = $rating - $whole;

			$round = round( $fraction, 2 );

			$output = '<span class="rating-stars">';

			for ( $count = 1; $count <= $whole; $count++ ) {
				$output .= '<i class="fas fa-star rated"></i>'; //3
			}
			$half = 0;
			if ( $round <= .24 ) {
				$half = 0;
			} else if ( $round >= .25 && $round <= .74 ) {
				$half   = 1;
				$output .= '<i class="fas fa-star-half-alt"></i>';
			} else if ( $round >= .75 ) {
				$half   = 1;
				$output .= '<i class="fas fa-star rated"></i>';
			}

			$unrated = 5 - ( $whole + $half );
			for ( $count = 1; $count <= $unrated; $count++ ) {
				$output .= '<i class="far fa-star"></i>';
			}

			$output .= '</span>';
		}

		return $output;
	}
}

if ( ! function_exists( 'rhea_stylish_meta' ) ) {

	function rhea_stylish_meta( $label, $post_meta_key, $icon, $postfix = '' ) {

		$post_meta = get_post_meta( get_the_ID(), $post_meta_key, true );

		$get_postfix = get_post_meta( get_the_ID(), $postfix, true );
		if ( isset( $post_meta ) && ! empty( $post_meta ) ) {
			?>
            <div class="rh_prop_card__meta">
				<?php
				if ( $label ) {
					?>
                    <span class="rhea_meta_titles"><?php echo esc_html( $label ); ?></span>
					<?php
				}
				?>
                <div class="rhea_meta_icon_wrapper">
					<?php
					if ( $icon ) {
						include RHEA_ASSETS_DIR . '/icons/' . $icon . '.svg';
					}
					?>
                    <span class="figure"><?php echo esc_html( $post_meta ); ?></span>
					<?php if ( isset( $postfix ) && ! empty( $postfix ) && ! empty( $get_postfix ) ) { ?>
                        <span class="label"><?php echo esc_html( get_post_meta( get_the_ID(), $postfix, true ) ); ?></span>
					<?php } ?>
                </div>
            </div>
			<?php
		}

	}
}

if ( ! function_exists( 'rhea_stylish_meta_smart' ) ) {

	function rhea_stylish_meta_smart( $label, $post_meta_key, $icon, $postfix = '' ) {

		$post_meta = get_post_meta( get_the_ID(), $post_meta_key, true );

		$get_postfix = get_post_meta( get_the_ID(), $postfix, true );
		if ( isset( $post_meta ) && ! empty( $post_meta ) ) {
			?>
            <div class="rh_prop_card__meta">
				<?php
				if ( $label ) {
					?>
                    <span class="rhea_meta_titles"><?php echo esc_html( $label ); ?></span>
					<?php
				}
				?>
                <div class="rhea_meta_icon_wrapper">
                    <span data-tooltip="<?php echo esc_html( $label ) ?>">
					<?php
					if ( $icon ) {
						include RHEA_ASSETS_DIR . '/icons/' . $icon . '.svg';
					}
					?>
                    </span>
                    <span class="rhea_meta_smart_box">
                    <span class="figure"><?php echo esc_html( $post_meta ); ?></span>
						<?php if ( isset( $postfix ) && ! empty( $postfix ) && ! empty( $get_postfix ) ) { ?>
                            <span class="label"><?php echo esc_html( get_post_meta( get_the_ID(), $postfix, true ) ); ?></span>
						<?php } ?>
                    </span>
                </div>
            </div>
			<?php
		}

	}
}

if ( ! function_exists( 'rhea_ultra_meta' ) ) {

	function rhea_ultra_meta( $label, $post_meta_key, $icon, $postfix = '' ) {

		$post_meta = get_post_meta( get_the_ID(), $post_meta_key, true );

		$get_postfix = get_post_meta( get_the_ID(), $postfix, true );
		if ( isset( $post_meta ) && ! empty( $post_meta ) ) {
			?>
            <div class="rhea_ultra_prop_card__meta">
                <h5 class="rhea-meta-icons-labels"><?php echo esc_html( $label ) ?></h5>
                <div class="rhea_ultra_meta_icon_wrapper">
                    <span class="rhea_ultra_meta_icon" title="<?php echo esc_attr( $label ) ?>">
					<?php
					if ( $icon ) {
						include RHEA_ASSETS_DIR . '/icons/' . $icon . '.svg';
					}
					?>
                    </span>
                    <span class="rhea_ultra_meta_box">
                    <span class="figure"><?php echo esc_html( $post_meta ); ?></span>
						<?php if ( isset( $postfix ) && ! empty( $postfix ) && ! empty( $get_postfix ) ) { ?>
                            <span class="label"><?php echo esc_html( get_post_meta( get_the_ID(), $postfix, true ) ); ?></span>
						<?php } ?>
                    </span>
                </div>
            </div>
			<?php
		}

	}
}

if ( ! function_exists( 'rhea_send_message_to_agent' ) ) {
	/**
	 * Handler for agent's contact form
	 */
	function rhea_send_message_to_agent() {
		if ( isset( $_POST['email'] ) ):
			/*
			 *  Verify Nonce
			 */
			$nonce = $_POST['nonce'];
			if ( ! wp_verify_nonce( $nonce, 'agent_message_nonce' ) ) {
				echo json_encode( array(
					'success' => false,
					'message' => ' <span class="rhea_error_log"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Unverified Nonce!', 'realhomes-elementor-addon' ) . '</span>',
				) );
				die;
			}

			/* Verify Google reCAPTCHA */
			ere_verify_google_recaptcha();

			/* Sanitize and Validate Target email address that is coming from agent form */
			$to_email = sanitize_email( $_POST['target'] );
			$to_email = is_email( $to_email );
			if ( ! $to_email ) {
				echo json_encode( array(
					'success' => false,
					'message' => ' <span class="rhea_error_log"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Target Email address is not properly configured!', 'realhomes-elementor-addon' ) . '</span>',
				) );
				die;
			}

			/* Sanitize and Validate contact form input data */
			$from_name  = sanitize_text_field( $_POST['name'] );
			$from_phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
			$message    = stripslashes( $_POST['message'] );

			/*
			 * From email
			 */
			$from_email = sanitize_email( $_POST['email'] );
			$from_email = is_email( $from_email );
			if ( ! $from_email ) {
				echo json_encode( array(
					'success' => false,
					'message' => ' <span class="rhea_error_log"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Provided Email address is invalid!', 'realhomes-elementor-addon' ) . ' </span>',
				) );
				die;
			}

			/*
			 * Email Subject
			 */
			$is_agency_form = ( isset( $_POST['form_of'] ) && 'agency' == $_POST['form_of'] );
			$form_of        = $is_agency_form ? esc_html__( 'using agency contact form at', 'realhomes-elementor-addon' ) : esc_html__( 'using agent contact form at', 'realhomes-elementor-addon' );
			$email_subject  = esc_html__( 'New message sent by', 'realhomes-elementor-addon' ) . ' ' . $from_name . ' ' . $form_of . ' ' . get_bloginfo( 'name' );

			/*
			 * Email body
			 */
			$email_body = array();

			if ( isset( $_POST['property_title'] ) ) {
				$property_title = sanitize_text_field( $_POST['property_title'] );
				if ( ! empty( $property_title ) ) {
					$email_body[] = array(
						'name'  => esc_html__( 'Property Title', 'realhomes-elementor-addon' ),
						'value' => $property_title,
					);
				}
			}

			if ( isset( $_POST['property_permalink'] ) ) {
				$property_permalink = esc_url( $_POST['property_permalink'] );
				if ( ! empty( $property_permalink ) ) {
					$email_body[] = array(
						'name'  => esc_html__( 'Property URL', 'realhomes-elementor-addon' ),
						'value' => $property_permalink,
					);
				}
			}

			$email_body[] = array(
				'name'  => esc_html__( 'Name', 'realhomes-elementor-addon' ),
				'value' => $from_name,
			);

			$email_body[] = array(
				'name'  => esc_html__( 'Email', 'realhomes-elementor-addon' ),
				'value' => $from_email,
			);

			if ( ! empty( $from_phone ) ) {
				$email_body[] = array(
					'name'  => esc_html__( 'Contact Number', 'realhomes-elementor-addon' ),
					'value' => $from_phone,
				);
			}

			$email_body[] = array(
				'name'  => esc_html__( 'Message', 'realhomes-elementor-addon' ),
				'value' => $message,
			);

			if ( '1' == get_option( 'inspiry_gdpr_in_email', '0' ) ) {
				$GDPR_agreement = $_POST['gdpr'];
				if ( ! empty( $GDPR_agreement ) ) {
					$email_body[] = array(
						'name'  => esc_html__( 'GDPR Agreement', 'realhomes-elementor-addon' ),
						'value' => $GDPR_agreement,
					);
				}
			}

			$email_body = ere_email_template( $email_body, 'agent_contact_form' );

			/*
			 * Email Headers ( Reply To and Content Type )
			 */
			$headers   = array();
			$headers[] = "Reply-To: $from_name <$from_email>";
			$headers[] = "Content-Type: text/html; charset=UTF-8";
			$headers   = apply_filters( "inspiry_agent_mail_header", $headers );    // just in case if you want to modify the header in child theme

			/* Send copy of message to admin if configured */
			$theme_send_message_copy = get_option( 'theme_send_message_copy', 'false' );
			if ( $theme_send_message_copy == 'true' ) {
				$cc_email = get_option( 'theme_message_copy_email' );
				$cc_email = explode( ',', $cc_email );
				if ( ! empty( $cc_email ) ) {
					foreach ( $cc_email as $ind_email ) {
						$ind_email = sanitize_email( $ind_email );
						$ind_email = is_email( $ind_email );
						if ( $ind_email ) {
							$headers[] = "Cc: $ind_email";
						}
					}
				}
			}

			if ( wp_mail( $to_email, $email_subject, $email_body, $headers ) ) {

				if ( '1' === get_option( 'ere_agency_form_webhook_integration', '0' ) && $is_agency_form ) {
					ere_forms_safe_webhook_post( $_POST, 'agency_contact_form' );
				} else if ( '1' === get_option( 'ere_agent_form_webhook_integration', '0' ) ) {
					ere_forms_safe_webhook_post( $_POST, 'agent_contact_form' );
				}

				echo json_encode( array(
					'success' => true,
					'message' => ' <span class="rhea_success_log"><i class="far fa-check-circle"></i> ' . esc_html__( 'Message Sent Successfully!', 'realhomes-elementor-addon' ) . '</span>',
				) );
			} else {
				echo json_encode( array(
						'success' => false,
						'message' => ' <span class="rhea_error_log"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Server Error: WordPress mail function failed!', 'realhomes-elementor-addon' ) . '</span>',
					)
				);
			}

		else:
			echo json_encode( array(
					'success' => false,
					'message' => ' <span class="rhea_error_log"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Invalid Request !', 'realhomes-elementor-addon' ) . '</span>',
				)
			);
		endif;

		do_action( 'inspiry_after_agent_form_submit' );

		die;
	}

	add_action( 'wp_ajax_nopriv_rhea_send_message_to_agent', 'rhea_send_message_to_agent' );
	add_action( 'wp_ajax_rhea_send_message_to_agent', 'rhea_send_message_to_agent' );
}

if ( ! function_exists( 'rhea_schedule_tour_form_mail' ) ) {
	/**
	 * Handler for schedule form email.
	 */
	function rhea_schedule_tour_form_mail() {

		if ( isset( $_POST['email'] ) ):

			// Verify Nonce.
			$nonce = $_POST['nonce'];
			if ( ! wp_verify_nonce( $nonce, 'schedule_tour_form_nonce' ) ) {
				echo json_encode( array(
					'success' => false,
					'message' => ' <label class="error"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Unverified Nonce!', 'realhomes-elementor-addon' ) . '</label>',
				) );
				die;
			}

			// Sanitize and Validate target email address that is coming from the form.
			$to_email = sanitize_email( $_POST['target'] );
			$to_email = is_email( $to_email );
			if ( ! $to_email ) {
				echo json_encode( array(
					'success' => false,
					'message' => ' <label class="error"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Target Email address is not properly configured!', 'realhomes-elementor-addon' ) . '</label>',
				) );
				die;
			}

			// Sanitize and validate form input data.
			$from_name = sanitize_text_field( $_POST['name'] );
			$date      = sanitize_text_field( $_POST['date'] );
			$message   = stripslashes( $_POST['message'] );

			// From email.
			$from_email = sanitize_email( $_POST['email'] );
			$from_email = is_email( $from_email );
			if ( ! $from_email ) {
				echo json_encode( array(
					'success' => false,
					'message' => ' <label class="error"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Provided Email address is invalid!', 'realhomes-elementor-addon' ) . ' </label>',
				) );
				die;
			}

			// Email Subject.
			$email_subject = esc_html__( 'New message sent by', 'realhomes-elementor-addon' ) . ' ' . $from_name . ' ' . esc_html__( 'using schedule tour form at', 'realhomes-elementor-addon' ) . ' ' . get_bloginfo( 'name' );

			// Email Body.
			$email_body = array();

			$email_body[] = array(
				'name'  => esc_html__( 'Name', 'realhomes-elementor-addon' ),
				'value' => $from_name,
			);

			$email_body[] = array(
				'name'  => esc_html__( 'Email', 'realhomes-elementor-addon' ),
				'value' => $from_email,
			);

			if ( ! empty( $date ) ) {
				$timestamp    = strtotime( $date );
				$email_body[] = array(
					'name'  => esc_html__( 'Desired Date & Time', 'realhomes-elementor-addon' ),
					'value' => date_i18n( get_option( 'date_format' ), $timestamp ) . ' ' . date_i18n( get_option( 'time_format' ), $timestamp ),
				);
			}

			$email_body[] = array(
				'name'  => esc_html__( 'Message', 'realhomes-elementor-addon' ),
				'value' => $message,
			);

			// Apply default emil template.
			$email_body = ere_email_template( $email_body, 'schedule_tour_form' );

			// Email Headers ( Reply To and Content Type ).
			$headers   = array();
			$headers[] = "Reply-To: $from_name <$from_email>";
			$headers[] = "Content-Type: text/html; charset=UTF-8";
			$headers   = apply_filters( "inspiry_schedule_tour_form_mail_header", $headers ); // just in case if you want to modify the header in child theme

			if ( wp_mail( $to_email, $email_subject, $email_body, $headers ) ) {
				echo json_encode( array(
					'success' => true,
					'message' => ' <label class="success"><i class="far fa-check-circle"></i> ' . esc_html__( 'Message Sent Successfully!', 'realhomes-elementor-addon' ) . '</label>',
				) );
			} else {
				echo json_encode( array(
						'success' => false,
						'message' => ' <label class="error"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Server Error: WordPress mail function failed!', 'realhomes-elementor-addon' ) . '</label>',
					)
				);
			}

		else:
			echo json_encode( array(
					'success' => false,
					'message' => ' <label class="error"><i class="fas fa-exclamation-circle"></i> ' . esc_html__( 'Invalid Request !', 'realhomes-elementor-addon' ) . '</label>',
				)
			);
		endif;

		die;
	}

	add_action( 'wp_ajax_nopriv_rhea_schedule_tour_form_mail', 'rhea_schedule_tour_form_mail' );
	add_action( 'wp_ajax_rhea_schedule_tour_form_mail', 'rhea_schedule_tour_form_mail' );
}

if ( ! function_exists( 'rhea_safe_include_svg' ) ) {
	/**
	 * Includes svg file in the RHEA Plugin.
	 *
	 * @since 0.7.2
	 *
	 * @param string $file
	 *
	 */
	function rhea_safe_include_svg( $file ) {
		$file = RHEA_ASSETS_DIR . $file;
		if ( file_exists( $file ) ) {
			include( $file );
		}
	}

}

if ( ! function_exists( 'rhea_lightbox_data_attributes' ) ) {

	function rhea_lightbox_data_attributes( $widget_id, $post_id, $classes = '' ) {

		$REAL_HOMES_property_map = get_post_meta( $post_id, 'REAL_HOMES_property_map', true );
		$property_location       = get_post_meta( $post_id, 'REAL_HOMES_property_location', true );

		if ( has_post_thumbnail() ) {
			$image_id         = get_post_thumbnail_id();
			$image_attributes = wp_get_attachment_image_src( $image_id, 'property-thumb-image' );
			if ( ! empty( $image_attributes[0] ) ) {
				$current_property_data = $image_attributes[0];
			}
		} else {
			$current_property_data = get_inspiry_image_placeholder_url( 'property-thumb-image' );
		}

		if ( ! empty( $property_location ) && $REAL_HOMES_property_map !== '1' ) {
			?>
            class="rhea_trigger_map rhea_facnybox_trigger-<?php echo esc_attr( $widget_id . ' ' . $classes ); ?>" data-rhea-map-source="rhea-map-source-<?php echo esc_attr( $widget_id ); ?>" data-rhea-map-location="<?php echo esc_attr( $property_location ); ?>" data-rhea-map-title="<?php echo esc_attr( get_the_title() ); ?>" data-rhea-map-price="<?php echo esc_attr( ere_get_property_price() ); ?>" data-rhea-map-thumb="<?php echo esc_attr( $current_property_data ) ?>"
			<?php
		}
	}
}

if ( ! function_exists( 'rhea_get_available_menus' ) ) {
	/**
	 * Get Available Menus
	 *
	 * @since 0.9.7
	 *
	 * @return array
	 */
	function rhea_get_available_menus() {
		$available_menues = wp_get_nav_menus();
		$options          = array();
		if ( ! empty( $available_menues ) ) {
			foreach ( $available_menues as $menu ) {
				$options[ $menu->slug ] = $menu->name;
			}
		}

		return $options;
	}
}

/**
 * Process additional fields for property elementor widgets
 * This method is generating HTML and returns nothing.
 *
 * @since 0.9.9
 *
 * @param int    $property_id
 * @param string $type
 * @param string $variation
 *
 * @return void
 */
function rhea_property_widgets_additional_fields( int $property_id, string $type = 'all', string $variation = 'modern' ) {
	/**
	 * Add property additional fields to the property listing cards
	 */
	$additional_fields = rhea_get_additional_fields( $type );

	if ( ! empty( $additional_fields ) ) {
		foreach ( $additional_fields as $field ) {
			$single_value = true;

			if ( 'checkbox_list' == $field['field_type'] ) {
				$single_value = false;
			}

			$value = get_post_meta( $property_id, $field['field_key'], $single_value );
			if ( ! empty( $value ) ) {

				if ( is_array( $value ) ) {
					$value = implode( ', ', $value );
				}

				if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
					$field['field_name'] = apply_filters( 'wpml_translate_single_string', $field['field_name'], 'Additional Fields', $field['field_name'] . ' Label', ICL_LANGUAGE_CODE );
				}

				if ( $variation == 'classic' ) {
					?>
                    <div class="rhea_meta_wrapper additional-field-wrap">
                        <div class="rhea_meta_wrapper_inner">
							<?php
							if ( ! empty ( $field['field_icon'] ) ) {
								?>
                                <i class="<?php echo esc_attr( $field['field_icon'] ); ?>" aria-hidden="true"></i>
								<?php
							}
							?>
                            <span class="figure">
                                <span class="figure"><?php echo esc_html( $value ); ?></span>
                                <?php
                                if ( $field['field_name'] ) {
	                                ?>
                                    <span class="rhea_meta_titles"><?php echo esc_html( $field['field_name'] ); ?></span>
	                                <?php
                                }
                                ?>
                            </span>
                        </div>
                    </div>
					<?php
				} else {
					?>
                    <div class="rh_prop_card__meta additional-field">
						<?php
						if ( $field['field_name'] ) {
							?>
                            <span class="rhea_meta_titles"><?php echo esc_html( $field['field_name'] ); ?></span>
							<?php
						}
						?>
                        <div class="rhea_meta_icon_wrapper">
							<?php
							if ( ! empty ( $field['field_icon'] ) ) {
								?>
                                <i class="<?php echo esc_attr( $field['field_icon'] ); ?>" aria-hidden="true"></i>
								<?php
							}
							?>
                            <span class="figure"><?php echo esc_html( $value ); ?></span>
                        </div>
                    </div>
					<?php
				}
			}
		}
	}
}

/**
 * Return a valid list of property additional fields
 *
 * @since 0.9.9
 *
 * @param string $location
 *
 * @return array $build_fields
 */
function rhea_get_additional_fields( string $location = 'all' ): array {

	$additional_fields = get_option( 'inspiry_property_additional_fields' );
	$build_fields      = array();

	if ( ! empty( $additional_fields['inspiry_additional_fields_list'] ) ) {
		foreach ( $additional_fields['inspiry_additional_fields_list'] as $field ) {

			// Ensure all required values of a field are available then add it to the fields list
			if ( ( $location == 'all' || ( ! empty( $field['field_display'] ) && in_array( $location, $field['field_display'] ) ) ) && ! empty( $field['field_type'] ) && ! empty( $field['field_name'] ) ) {

				// Prepare select field options list
				if ( in_array( $field['field_type'], array( 'select', 'checkbox_list', 'radio' ) ) ) {
					if ( empty( $field['field_options'] ) ) {
						$field['field_type'] = 'text';
					} else {
						$options                = explode( ',', $field['field_options'] );
						$options                = array_filter( array_map( 'trim', $options ) );
						$field['field_options'] = array_combine( $options, $options );
					}
				}

				// Set the field icon and unique key
				$field['field_icon'] = empty( $field['field_icon'] ) ? '' : $field['field_icon'];
				$field['field_key']  = 'inspiry_' . strtolower( preg_replace( '/\s+/', '_', $field['field_name'] ) );

				// Add final field to the fields list
				$build_fields[] = $field;
			}
		}
	}

	// Return additional fields array
	return $build_fields;
}

if ( ! function_exists( 'rhea_get_sample_property_id' ) ) {
	/**
	 * Return Sample Property ID to be used for Single Property Elementor Design from Editor Side
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	function rhea_get_sample_property_id() {
		return (int)get_option( 'realhomes_sample_property_id', '' );
	}
}

if ( ! function_exists( 'rhea_is_preview_mode' ) ) {
	/**
	 * Check if screen is in Elementor preview mode
	 *
	 * @since 2.1.0
	 *
	 * @return bool
	 */
	function rhea_is_preview_mode() {

		if ( class_exists( '\Elementor\Plugin' ) ) {

            // TODO: This return type is generating error on 404 and archives (pages with no id assigned) need to discuss and improve this one
            if( 0 < get_the_ID() ) {
	            return \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor();
            }
			return false;
		}

		return false;
	}
}

if ( ! function_exists( 'rhea_print_no_result' ) ) {
	/**
	 * Print HTML when no results found
	 *
	 * @since 2.1.0
	 *
	 * @param string $custom_no_result_text
	 */
	function rhea_print_no_result( $custom_no_result_text = '' ) {

		$no_result = esc_html__( 'No Information Added! Please Edit Property To Add Information.', 'realhomes-elementor-addon' );
		if ( ! empty( $custom_no_result_text ) ) {
			$no_result = esc_html( $custom_no_result_text );
		}
		?>
        <p class="rhea-no-results"><i class="fas fa-exclamation-triangle"></i> <?php echo esc_html( $no_result ); ?></p>
		<?php

	}
}

if ( ! function_exists( 'rhea_print_no_result_for_editor' ) ) {
	/**
	 * Print HTML when no results found in Elementor Editor
	 *
	 * @since 2.1.0
	 *
	 * @param string $custom_no_result_text
	 */
	function rhea_print_no_result_for_editor( $custom_no_result_text = '' ) {

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
            <div class="rhea-section-editor-class">
				<?php
				rhea_print_no_result( $custom_no_result_text );
				?>
            </div>
			<?php
		}

	}
}

if ( ! function_exists( 'rhea_is_rvr_enabled' ) ) {
	/**
	 * Check if Realhomes Vacation Rentals plugin is activated and enabled
     *
     * @since      2.2.0
	 *
	 * @return bool
	 */
	function rhea_is_rvr_enabled() {
		$rvr_settings = get_option( 'rvr_settings' );
		$rvr_enabled  = isset( $rvr_settings['rvr_activation'] ) ? $rvr_settings['rvr_activation'] : false;

		if ( $rvr_enabled && class_exists( 'Realhomes_Vacation_Rentals' ) ) {
			return true;
		}

		return false;
	}
}
