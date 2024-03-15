<?php
/**
 * Homepage Property Slider
 *
 * @package    realhomes
 * @subpackage modern
 */

$number_of_slides = intval( get_post_meta( get_the_ID(), 'theme_number_of_slides', true ) );
if ( ! $number_of_slides ) {
	$number_of_slides = -1;
}

$slider_args = array(
	'post_type'      => 'property',
	'posts_per_page' => $number_of_slides,
	'meta_query'     => array(
		array(
			'key'     => 'REAL_HOMES_add_in_slider',
			'value'   => 'yes',
			'compare' => 'LIKE',
		),
	),
);

$slider_query         = new WP_Query( $slider_args );

if ( $slider_query->have_posts() ) :
	?>
	<!-- Slider -->
	<section id="rh_slider__home" class="rh_slider rh_slider_mod clearfix">
		<div class="flexslider loading rh_home_load_height">
			<ul class="slides">
				<?php
				while ( $slider_query->have_posts() ) :
					$slider_query->the_post();
					$slide_id           = get_the_ID();
					$slider_image_id    = get_post_meta( $slide_id, 'REAL_HOMES_slider_image', true );
					$property_size      = get_post_meta( $slide_id, 'REAL_HOMES_property_size', true );
					$size_postfix       = get_post_meta( $slide_id, 'REAL_HOMES_property_size_postfix', true );
					$property_bedrooms  = get_post_meta( $slide_id, 'REAL_HOMES_property_bedrooms', true );
					$property_bathrooms = get_post_meta( $slide_id, 'REAL_HOMES_property_bathrooms', true );
					$property_address   = get_post_meta( $slide_id, 'REAL_HOMES_property_address', true );
					$is_featured        = get_post_meta( $slide_id, 'REAL_HOMES_featured', true );
					if ( $slider_image_id ) {
						$slider_image_url = wp_get_attachment_url( $slider_image_id ); ?>
						<li>

							<a class="slide" href="<?php the_permalink(); ?>"
							   style="background: url('<?php echo esc_url( $slider_image_url ); ?>') 50% 50% no-repeat;
									   background-size: cover;">
							</a>

							<div class="rh_slide__desc">

								<div class="rh_slide__desc_wrap">

									<?php if ( ! empty( $is_featured ) ) : ?>
										<div class="rh_label rh_label__slide">
											<div class="rh_label__wrap">
												<?php realhomes_featured_label(); ?>
												<span></span>
											</div>
										</div>
										<!-- /.rh_label -->
									<?php endif; ?>

									<h3>
										<a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
									</h3>

									<?php if ( ! empty( $property_address ) ) : ?>
										<p><?php echo esc_html( $property_address ); ?></p>
									<?php endif; ?>

                                    <div class="rh_slide__meta_wrap">
	                                    <?php
	                                    // RVR Property Meta.
	                                    if ( inspiry_is_rvr_enabled() ) {
		                                    $rvr_guests_capacity = get_post_meta( $slide_id, 'rvr_guests_capacity', true );
		                                    if ( ! empty( $rvr_guests_capacity ) ) :
			                                    ?>
                                                <div class="rh_slide__prop_meta rvr_guest_capacity">
                                                    <span class="rh_meta_titles">
                                                        <?php
                                                        $inspiry_rvr_guests_field_label = get_option( 'inspiry_rvr_guests_field_label' );
                                                        if ( ! empty( $inspiry_rvr_guests_field_label ) ) {
	                                                        echo esc_html( $inspiry_rvr_guests_field_label );
                                                        } else {
	                                                        esc_html_e( 'Capacity', 'framework' );
                                                        }
                                                        ?>
                                                    </span>
                                                    <div>
					                                    <?php
					                                    inspiry_safe_include_svg( 'images/guests-icons.svg', '/common/' );
					                                    ?>
                                                        <span class="figure"><?php echo esc_html( $rvr_guests_capacity ); ?></span>
                                                    </div>
                                                </div><!-- /.rh_slide__prop_meta -->
		                                    <?php
		                                    endif;

		                                    $rvr_min_stay = get_post_meta( $slide_id, 'rvr_min_stay', true );
		                                    if ( ! empty( $rvr_min_stay ) ) : ?>
                                                <div class="rh_slide__prop_meta rvr_min_stay">
                                                    <span class="rh_meta_titles">
                                                        <?php
                                                        $inspiry_rvr_min_stay_label = get_option( 'inspiry_rvr_min_stay_label' );
                                                        if ( ! empty( $inspiry_rvr_min_stay_label ) ) {
	                                                        echo esc_html( $inspiry_rvr_min_stay_label );
                                                        } else {
	                                                        esc_html_e( 'Min Stay', 'framework' );
                                                        }
                                                        ?>
                                                    </span>
                                                    <div>
					                                    <?php inspiry_safe_include_svg( '/images/icons/icon-min-stay.svg' ); ?>
                                                        <span class="figure"><?php echo esc_html( $rvr_min_stay ); ?></span>
                                                    </div>
                                                </div><!-- /.rh_slide__prop_meta -->
		                                    <?php
		                                    endif;
	                                    } else {
		                                    if ( ! empty( $property_bedrooms ) ) :
			                                    ?>
                                                <div class="rh_slide__prop_meta">
                                                    <span class="rh_meta_titles">
                                                        <?php
                                                        $bedrooms_label = get_option( 'inspiry_bedrooms_field_label' );
                                                        if ( ! empty( $bedrooms_label ) ) {
	                                                        echo esc_html( $bedrooms_label );
                                                        } else {
	                                                        esc_html_e( 'Bedrooms', 'framework' );
                                                        }
                                                        ?>
                                                    </span>
                                                    <div>
					                                    <?php inspiry_safe_include_svg( '/images/icons/icon-bed.svg' ); ?>
                                                        <span class="figure"><?php echo esc_html( $property_bedrooms ); ?></span>
                                                    </div>
                                                </div><!-- /.rh_slide__prop_meta -->
		                                    <?php
		                                    endif;

		                                    if ( ! empty( $property_bathrooms ) ) :
			                                    ?>
                                                <div class="rh_slide__prop_meta">
                                                    <span class="rh_meta_titles">
                                                        <?php
                                                        $bathrooms_label = get_option( 'inspiry_bathrooms_field_label' );

                                                        if ( ! empty( $bathrooms_label ) ) {
	                                                        echo esc_html( $bathrooms_label );
                                                        } else {
	                                                        esc_html_e( 'Bathrooms', 'framework' );
                                                        }

                                                        ?>
                                                    </span>
                                                    <div>
					                                    <?php inspiry_safe_include_svg( '/images/icons/icon-shower.svg' ); ?>
                                                        <span class="figure"><?php echo esc_html( $property_bathrooms ); ?></span>
                                                    </div>
                                                </div><!-- /.rh_slide__prop_meta -->
		                                    <?php
		                                    endif;
	                                    }

	                                    if ( ! empty( $property_size ) ) :
		                                    ?>
                                            <div class="rh_slide__prop_meta">
                                                    <span class="rh_meta_titles">
                                                        <?php
                                                        $area_label = get_option( 'inspiry_area_field_label' );
                                                        if ( ! empty( $area_label ) ) {
	                                                        echo esc_html( $area_label );
                                                        } else {
	                                                        esc_html_e( 'Area', 'framework' );
                                                        }
                                                        ?>
                                                    </span>
                                                <div>
				                                    <?php inspiry_safe_include_svg( '/images/icons/icon-area.svg' ); ?>
                                                    <span class="figure">
														    <?php echo esc_html( $property_size ); ?>
													    </span>
				                                    <?php if ( ! empty( $size_postfix ) ) : ?>
                                                        <span class="label">
															<?php echo esc_html( $size_postfix ); ?>
														</span>
				                                    <?php endif; ?>
                                                </div>
                                            </div><!-- /.rh_slide__prop_meta -->
	                                    <?php
	                                    endif;
	                                    ?>
                                    </div><!-- /.rh_slide__meta_wrap -->

									<div class="rh_slide_prop_price">
										<?php
										$price = null;

										if ( function_exists('ere_get_property_price') ) {
											$price = ere_get_property_price();
										}

										if ( $price ) {

											$statuses = get_the_terms( get_the_ID(), 'property-status' );
											if ( ! empty( $statuses ) && ! is_wp_error( $statuses ) ) {
												$statuses_names        = wp_list_pluck( $statuses, 'name' );
												$statuses_names_joined = implode( ', ', $statuses_names );
												echo '<div class="rh_price_sym">' . esc_html( $statuses_names_joined ) . '</div>';
											}

											echo '<span>' . esc_html( $price ) . '</span>';
										}
										?>
									</div>
									<!-- /.rh_slide_prop_price -->
								</div>
								<!-- /.rh_slide__desc_wrap -->

							</div>
							<!-- /.rh_slide__desc -->

						</li>
						<?php
					}
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		</div>
		<div class="rh_flexslider__nav_main">
			<a href="#" class="flex-prev rh_flexslider__prev">
				<?php inspiry_safe_include_svg( '/images/icons/icon-arrow-right.svg' ); ?>
			</a>
			<!-- /.rh_flexslider__prev -->
			<a href="#" class="flex-next rh_flexslider__next">
				<?php inspiry_safe_include_svg( '/images/icons/icon-arrow-right.svg' ); ?>
			</a>
			<!-- /.rh_flexslider__next -->
		</div>
	</section><!-- End Slider -->
	<?php
else :
	$slider_image_url = 'https://via.placeholder.com/2000x800&text=' . rawurlencode( esc_html__( 'No property is assigned to homepage slider!', 'framework' ) );
	?>
	<!-- Slider Placeholder -->
	<section id="rh_slider__home" class="rh_slider clearfix">
		<div class="flexslider loading rh_home_load_height">
			<ul class="slides">
				<li>
					<a class="slide" href="<?php echo esc_url( home_url( '/' ) ); ?>" style="background: url('<?php echo esc_url( $slider_image_url ); ?>') 50% 50% no-repeat; background-size: cover;"></a>
				</li>
			</ul>
		</div>
	</section><!-- End Slider Placeholder -->
	<?php
endif;
