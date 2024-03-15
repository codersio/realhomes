<?php
/**
 * Dashboard: Booking Card
 *
 * @package realhomes
 * @subpackage dashboard
 * @since 3.21.0
 */

$booking_id   = get_the_ID();
$booking_meta = get_post_custom( get_the_ID() );
$property_id  = intval( $booking_meta['rvr_property_id'][0] );

$property_size      = get_post_meta( $property_id, 'REAL_HOMES_property_size', true );
$size_postfix       = get_post_meta( $property_id, 'REAL_HOMES_property_size_postfix', true );
$property_bedrooms  = get_post_meta( $property_id, 'REAL_HOMES_property_bedrooms', true );
$property_bathrooms = get_post_meta( $property_id, 'REAL_HOMES_property_bathrooms', true );
$property_custom_id = get_post_meta( $property_id, 'REAL_HOMES_property_id', true );
?>
<div class="property-column-wrap">

    <div class="large-column-wrap">
        <div class="column column-thumbnail">
            <figure>
                <a href="<?php the_permalink(); ?>">
					<?php
					if ( has_post_thumbnail( $property_id ) ) {
						echo get_the_post_thumbnail( $property_id, 'modern-property-child-slider' );
					} else {
						inspiry_image_placeholder( 'modern-property-child-slider' );
					}
					?>
                </a>
            </figure>
        </div>
        <div class="column column-info">
            <div class="property-info-wrap">
                <h3 class="property-title">
                    <a href="<?php echo get_the_permalink( $property_id ); ?>" target="_blank"><?php echo get_the_title( $property_id ); ?></a>
                </h3>
                <p class="property-excerpt"><?php the_title(); ?></p>
                <ul class="property-meta">
					<?php
					$bedrooms_label  = get_option( 'inspiry_bedrooms_field_label' );
					$bathrooms_label = get_option( 'inspiry_bathrooms_field_label' );
					$area_label      = get_option( 'inspiry_area_field_label' );

					if ( ! empty( $property_bedrooms ) ) {
						?>
                        <li>
                            <span class="property-meta-label">
                              <?php
                              if ( ! empty( $bedrooms_label ) ) {
	                              echo esc_html( $bedrooms_label );
                              } else {
	                              esc_html_e( 'Bedrooms', 'framework' );
                              }
                              ?>
                            </span>
                            <div class="property-meta-icon">
								<?php inspiry_safe_include_svg( 'images/icon-bed.svg', '/common/' ); ?>
                                <span class="figure"><?php echo esc_html( $property_bedrooms ); ?></span>
                            </div>
                        </li>
						<?php
					}

					if ( ! empty( $property_bathrooms ) ) {
						?>
                        <li>
                            <span class="property-meta-label">
                               <?php
                               if ( ! empty( $bathrooms_label ) ) {
	                               echo esc_html( $bathrooms_label );
                               } else {
	                               esc_html_e( 'Bathrooms', 'framework' );
                               }
                               ?>
                            </span>
                            <div class="property-meta-icon">
								<?php inspiry_safe_include_svg( 'images/icon-shower.svg', '/common/' ); ?>
                                <span class="figure"><?php echo esc_html( $property_bathrooms ); ?></span>
                            </div>
                        </li>
						<?php
					}

					if ( ! empty( $property_size ) ) {
						?>
                        <li>
                            <span class="property-meta-label">
                                <?php
                                if ( ! empty( $area_label ) ) {
	                                echo esc_html( $area_label );
                                } else {
	                                esc_html_e( 'Area', 'framework' );
                                }
                                ?>
                            </span>
                            <div class="property-meta-icon">
								<?php inspiry_safe_include_svg( 'images/icon-area.svg', '/common/' ); ?>
                                <span class="figure"><?php echo esc_html( $property_size ); ?></span>
								<?php
								if ( ! empty( $size_postfix ) ) {
									?>
                                    <span class="property-meta-postfix"><?php echo esc_html( $size_postfix ); ?></span>
									<?php
								}
								?>
                            </div>
                        </li>
						<?php
					}

					/**
					 * This hook can be used to display more property meta fields
					 */
					do_action( 'realhomes_additional_property_listing_meta_fields', get_the_ID() );

					?>
                </ul>
            </div>
        </div>
    </div>

    <div class="small-column-wrap">
        <div class="column column-booked-on">
            <div class="booking-booked-on-wrap">
                <span class="property-date">
                    <strong class="property-meta-label"><?php esc_html_e( 'Booking On', 'framework' ); ?></strong>
                    <span><?php echo esc_html( date_format( date_create( $booking_meta['rvr_request_timestamp'][0] ), 'F d, Y' ) ); ?></span>
                    <span><?php echo esc_html( date_format( date_create( $booking_meta['rvr_request_timestamp'][0] ), 'H:i a' ) ); ?></span>
                </span>
            </div>
        </div>
        <div class="column column-booking-period">
            <div class="property-info-wrap">
                <span class="property-date">
                    <strong class="property-meta-label"><?php esc_html_e( 'Booking Period', 'framework' ); ?></strong>
                    <span><?php echo esc_html( date_format( date_create( $booking_meta['rvr_check_in'][0] ), 'F d, Y' ) ); ?></span>
                    <?php echo ' <strong>' . esc_html__( 'to', 'framework' ) . '</strong> '; ?>
                    <span><?php echo esc_html( date_format( date_create( $booking_meta['rvr_check_out'][0] ), 'F d, Y' ) ); ?></span>
                </span>
            </div>
        </div>
        <div class="column column-status">
			<?php
			$booking_status = $status_class = '';
			if ( ! empty( $booking_meta['rvr_booking_status'][0] ) ) {

				$booking_status = $booking_meta['rvr_booking_status'][0];

				switch ( $booking_status ) {
					case 'pending':
						$status_class = 'property-status-tag-pending';
						break;
					case 'cancelled':
						$status_class = 'property-status-tag-cancelled';
						break;
					case 'rejected':
						$status_class = 'property-status-tag-rejected';
						break;
				}
			}
			?>
            <div class="property-status-tag <?php echo esc_attr( $status_class ); ?>">
                <span class="booking-status-text">
                    <?php echo ! empty( $booking_status ) ? esc_html( ucfirst( $booking_meta['rvr_booking_status'][0] ) ) : esc_html__( 'NA', 'framework' ); ?>
                </span>
                <a class="booking-edit">
					<?php esc_html_e( 'Edit', 'framework' ); ?>
                </a>
				<?php
				$booking_statuses = array(
					'pending'   => esc_html__( 'Pending', 'framework' ),
					'rejected'  => esc_html__( 'Reject', 'framework' ),
					'cancelled' => esc_html__( 'Cancel', 'framework' ),
					'confirmed' => esc_html__( 'Confirm', 'framework' ),
				);
				?>
                <ul class="rvr-select-status">
					<?php
					foreach ( $booking_statuses as $status_key => $status_label ) {
						if ( $booking_status === $status_key ) {
							continue;
						}
						?>
                        <li class="<?php echo esc_attr( $status_key ); ?>" data-booking-id="<?php echo get_the_ID(); ?>" data-status="<?php echo esc_attr( $status_key ); ?>"><?php echo esc_html( $status_label ); ?></li>
						<?php
					}
					?>
                </ul>
            </div>
        </div>
        <div class="column column-price">
            <p class="property-price"><?php echo ere_format_amount( floatval( $booking_meta['rvr_total_price'][0] ) ); ?></p>
        </div>
        <div class="column column-booked-by">
            <p class="renter-name"><?php echo esc_html( $booking_meta['rvr_renter_name'][0] ); ?></p>
        </div>
    </div>

    <div class="property-actions-wrapper">

        <strong><?php esc_attr_e( 'Actions', 'framework' ); ?></strong>
        <a class="booking-details-view" href="#">
            <span class="booking-detail-shown">
                <i class="fas fa-eye"></i>
                <?php esc_html_e( 'Show Details', 'framework' ); ?>
            </span>
            <span class="booking-detail-hidden">
                <i class="fas fa-eye-slash"></i>
                <?php esc_html_e( 'Hide Details', 'framework' ); ?>
            </span>
        </a>
		<?php

		// Delete Booking Link
		if ( current_user_can( 'delete_posts' ) ) {
			?>
            <a class="delete" href="#">
                <i class="fas fa-trash"></i>
				<?php esc_html_e( 'Delete', 'framework' ); ?>
            </a>
            <span class="confirmation hide">
                <a class="remove-property" data-property-id="<?php the_ID(); ?>"
                        href="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
                        title="<?php esc_attr_e( 'Remove This Booking', 'framework' ); ?>">
                    <i class="fas fa-check confirm-icon"></i>
                    <i class="fas fa-spinner fa-spin loader hide"></i>
                    <?php esc_html_e( 'Confirm', 'framework' ); ?>
                </a>
                <a href="#" class="cancel">
                    <i class="fas fa-times"></i>
                    <?php esc_html_e( 'Cancel', 'framework' ); ?>
                </a>
            </span>
			<?php
		}
		?>
    </div>

    <div class="rvr-booking-details">
        <div class="rvr-booking-details-contents">
            <div class="rvr-booking-detail-area">
                <h4><?php esc_html_e( 'Booking Details', 'framework' ); ?></h4>
                <ul>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'Property', 'framework' ); ?></span>
                        <span class="booking-meta-value"><a href="<?php echo get_the_permalink( $property_id ); ?>" target="_blank"><?php echo get_the_title( $property_id ); ?></a></span>
                    </li>
                    <li class="booking-meta-value-highlight">
                        <span class="booking-meta-title"><?php esc_html_e( 'Property ID', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo esc_html( $property_custom_id ); ?></span>
                    </li>
                    <li class="booking-meta-value-highlight">
                        <span class="booking-meta-title"><?php esc_html_e( 'Booking ID', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php the_title(); ?></span>
                    </li>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'Check In Date', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo esc_html( date_format( date_create( $booking_meta['rvr_check_in'][0] ), 'F d, Y' ) ); ?></span>
                    </li>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'Check Out Date', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo esc_html( date_format( date_create( $booking_meta['rvr_check_out'][0] ), 'F d, Y' ) ); ?></span>
                    </li>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'No of Guests', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo intval( $booking_meta['rvr_adult'][0] ) + intval( isset( $booking_meta['rvr_child'][0] ) ?? 0 ); ?></span>
                    </li>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'Adults', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo esc_html( $booking_meta['rvr_adult'][0] ); ?></span>
                    </li>
					<?php
					if ( ! empty( $booking_meta['rvr_child'][0] ) ) {
						?>
                        <li>
                            <span class="booking-meta-title"><?php esc_html_e( 'Children', 'framework' ); ?></span>
                            <span class="booking-meta-value"><?php echo esc_html( $booking_meta['rvr_child'][0] ); ?></span>
                        </li>
						<?php
					}
					?>
                </ul>
            </div>
            <div class="rvr-booking-detail-area">
                <h4><?php esc_html_e( 'Renter Info', 'framework' ); ?></h4>
                <ul>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'Name', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo esc_html( $booking_meta['rvr_renter_name'][0] ); ?></span>
                    </li>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'Email', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo esc_html( $booking_meta['rvr_renter_email'][0] ); ?></span>
                    </li>
					<?php
					if ( ! empty( $booking_meta['rvr_renter_phone'][0] ) ) {
						?>
                        <li>
                            <span class="booking-meta-title"><?php esc_html_e( 'Phone', 'framework' ); ?></span>
                            <span class="booking-meta-value"><?php echo esc_html( $booking_meta['rvr_renter_phone'][0] ); ?></span>
                        </li>
						<?php
					}
					?>
                </ul>
            </div>
			<?php
			$additional_fees       = get_post_meta( get_the_ID(), 'rvr_additional_fees_paid', true );
			$additional_fees_total = 0;
			if ( ! empty( $additional_fees ) && is_array( $additional_fees ) ) {
				?>
                <div class="rvr-booking-detail-area">
                    <h4><?php esc_html_e( 'Additional Fees Details', 'framework' ); ?></h4>
                    <ul>
						<?php
						foreach ( $additional_fees as $fee_label => $fee_amount ) {
							$additional_fees_total += floatval( str_replace( '$', '', $fee_amount ) );
							?>
                            <li>
                                <span class="booking-meta-title"><?php echo esc_html( $fee_label ); ?></span>
                                <span class="booking-meta-value"><?php echo esc_html( $fee_amount ); ?></span>
                            </li>
							<?php
						}
						?>
                    </ul>
                </div>
				<?php
			}
			?>
            <div class="rvr-booking-detail-area">
                <h4><?php esc_html_e( 'Cost Details', 'framework' ); ?></h4>
                <ul>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'Staying Nights', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo esc_html( $booking_meta['rvr_staying_nights'][0] ); ?></span>
                    </li>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'Price Per Night', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo ere_format_amount( floatval( $booking_meta['rvr_price_per_night'][0] ) ) . ' x ' . esc_html( $booking_meta['rvr_staying_nights'][0] ); ?></span>
                    </li>
                    <li>
                        <span class="booking-meta-title"><?php esc_html_e( 'Staying Night Price', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo ere_format_amount( floatval( $booking_meta['rvr_price_staying_nights'][0] ) ); ?></span>
                    </li>
					<?php

					if ( ! empty( $booking_meta['rvr_extra_guests_cost'][0] ) ) {
						?>
                        <li>
                            <span class="booking-meta-title"><?php esc_html_e( 'Extra Guests Charges', 'framework' ); ?></span>
                            <span class="booking-meta-value"><?php echo ere_format_amount( floatval( $booking_meta['rvr_extra_guests_cost'][0] ) ); ?></span>
                        </li>
						<?php
					}

					if ( ! empty( $additional_fees_total ) ) {
						?>
                        <li>
                            <span class="booking-meta-title"><?php esc_html_e( 'Additional Fees', 'framework' ); ?></span>
                            <span class="booking-meta-value"><?php echo ere_format_amount( floatval( $additional_fees_total ) ); ?></span>
                        </li>
						<?php
					}

					if ( ! empty( $booking_meta['rvr_services_charges'][0] ) ) {
						?>
                        <li>
                            <span class="booking-meta-title"><?php esc_html_e( 'Service Charges', 'framework' ); ?></span>
                            <span class="booking-meta-value"><?php echo ere_format_amount( floatval( $booking_meta['rvr_services_charges'][0] ) ); ?></span>
                        </li>
						<?php
					}
					?>
                    <li class="booking-sub-total-price">
                        <span class="booking-meta-title"><?php esc_html_e( 'Sub-Total', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo ere_format_amount( floatval( $booking_meta['rvr_subtotal_price'][0] ) ); ?></span>
                    </li>
					<?php
					if ( ! empty( $booking_meta['rvr_govt_tax'][0] ) ) {
						?>
                        <li>
                            <span class="booking-meta-title"><?php esc_html_e( 'Government Tax', 'framework' ); ?></span>
                            <span class="booking-meta-value"><?php echo ere_format_amount( floatval( $booking_meta['rvr_govt_tax'][0] ) ); ?></span>
                        </li>
						<?php
					}
					?>
                    <li class="booking-total-price">
                        <span class="booking-meta-title"><?php esc_html_e( 'Total Price', 'framework' ); ?></span>
                        <span class="booking-meta-value"><?php echo ere_format_amount( floatval( $booking_meta['rvr_total_price'][0] ) ); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>