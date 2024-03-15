<?php
/**
 * Dashboard: Bookings List
 *
 * @package realhomes
 * @subpackage dashboard
 * @since 3.21.0
 */

global $paged, $posts_per_page, $property_status_filter, $dashboard_posts_query, $current_module;

$current_user = wp_get_current_user();

$bookings_args = array(
	'post_type'   => 'booking',
	'post_status' => 'publish',
	'paged'       => $paged,
	'author'      => $current_user->ID,
);

// Posts per page parameter
$bookings_args['posts_per_page'] = realhomes_dashboard_posts_per_page();

// Add booking status filter parameter
if ( isset( $_GET['property_status_filter'] ) && ! empty( $_GET['property_status_filter'] ) ) {
	$bookings_args['meta_key']     = 'rvr_booking_status';
	$bookings_args['meta_value']   = sanitize_text_field( $_GET['property_status_filter'] );
	$bookings_args['meta_compare'] = '=';
}

/**
 * Add searched parameter.
 * Note: Adding based on the properties settings.
 */
if ( isset( $_GET['prop_search'] ) && 'show' == get_option( 'inspiry_my_properties_search', 'show' ) ) {
	$bookings_args['s'] = sanitize_text_field( $_GET['prop_search'] );
	printf( '<div class="dashboard-notice"><p>%s <strong>%s</strong></p></div>', esc_html__( 'Search results for: ', 'framework' ), esc_html( $_GET['prop_search'] ) );
}

$dashboard_posts_query = new WP_Query( apply_filters( 'realhomes_dashboard_bookings_args', $bookings_args ) );
?>
<div id="dashboard-bookings" class="dashboard-bookings">
	<?php
	get_template_part( 'common/dashboard/top-nav' );
	if ( $dashboard_posts_query->have_posts() ) {
		?>
        <div class="dashboard-posts-list">
			<?php get_template_part( 'common/dashboard/partials/booking-columns' ); ?>
            <div class="dashboard-posts-list-body">
				<?php
				while ( $dashboard_posts_query->have_posts() ) {
					$dashboard_posts_query->the_post();
					get_template_part( 'common/dashboard/partials/booking-card' );
				}
				wp_reset_postdata();
				?>
            </div>
        </div>
		<?php
	} else {
		$bookings_not_found = esc_html__( 'No Booking Found!', 'framework' );
		if ( isset( $_GET['property_status_filter'] ) && ! empty( $_GET['property_status_filter'] ) ) {
			$bookings_not_found = sprintf( esc_html__( 'No "%s" Bookings Found!', 'framework' ), ucfirst( sanitize_text_field( $_GET['property_status_filter'] ) ) );
		}

		realhomes_dashboard_no_items( $bookings_not_found );
	}
	?>
	<?php get_template_part( 'common/dashboard/bottom-nav' ); ?>
</div><!-- #dashboard-bookings -->