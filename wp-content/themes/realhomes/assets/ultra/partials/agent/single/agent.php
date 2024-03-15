<?php
/**
 * Single Agent
 *
 * Template for single agent.
 *
 * @since      4.0.0
 * @package    realhomes
 * @subpackage ultra
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single-agent' ) ) {
	?>
    <div class="rh-page-container container">
        <div class="row">
            <div class="col-8 main-content">
				<?php
				get_template_part( 'assets/ultra/partials/page-head' );

				// Display any contents after the page head and before the contents.
				do_action( 'inspiry_before_page_contents' );
				?>
                <main id="main" class="rh-main main">
					<?php
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();

							get_template_part( 'assets/ultra/partials/agent/single/card' );
						}
					}
					?>
                    <div class="agent-contact-form-wrapper">
                        <h3 class="agent-contact-form-heading rh-page-heading"><?php esc_html_e( 'Contact me', 'framework' ); ?></h3>
						<?php get_template_part( 'assets/ultra/partials/agent/single/contact-form' ); ?>
                    </div>
					<?php
					if ( 'show' === get_option( 'inspiry_agent_single_properties', 'show' ) ) {
						?>
                        <h3 class="agent-properties-heading rh-page-heading"><?php esc_html_e( 'My Listings', 'framework' ); ?></h3>
                        <div class="agent-properties">
							<?php
							global $paged;

							$number_of_properties = get_option( 'theme_number_of_properties_agent', '6' );

							$agent_properties_args = array(
								'post_type'      => 'property',
								'posts_per_page' => intval( $number_of_properties ),
								'meta_query'     => array(
									array(
										'key'     => 'REAL_HOMES_agents',
										'value'   => get_the_ID(),
										'compare' => '=',
									),
								),
								'paged'          => $paged,
							);

							$agent_properties_listing_query = new WP_Query( apply_filters( 'inspiry_agent_single_properties', $agent_properties_args ) );

							if ( $agent_properties_listing_query->have_posts() ) {
								while ( $agent_properties_listing_query->have_posts() ) {
									$agent_properties_listing_query->the_post();

									// Display Property for Listing
									get_template_part( 'assets/ultra/partials/properties/list-card-1' );
								}

								inspiry_theme_pagination( $agent_properties_listing_query->max_num_pages );

								wp_reset_postdata();
							} else {
								?>
                                <div class="rh-alert-wrapper">
                                    <h4 class="no-results"><?php esc_html_e( 'No Property Found!', 'framework' ) ?></h4>
                                </div>
								<?php
							}
							?>
                        </div>
						<?php
					}
					?>
                </main>
            </div>
			<?php
			$attached_sidebar = RealHomes_Custom_Sidebar::get_attached_sidebar( 'agent-sidebar' );
			if ( is_active_sidebar( $attached_sidebar ) ) {
				?>
                <div class="col-4 sidebar-content">
	                <?php get_sidebar( 'agent' ); ?>
                </div>
				<?php
			}
			?>
        </div>
    </div><!-- .rh-page-container -->
	<?php
}
get_footer();