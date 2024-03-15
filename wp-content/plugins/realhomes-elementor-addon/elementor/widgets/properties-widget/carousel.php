<?php
/**
 * This file contains the carousel variation of Ultra properties widget.
 *
 * @version 2.2.0
 */

global $settings, $widget_id, $properties_query;
?>
<section class="rhea-ultra-properties-one-section rhea-ultra-tooltip rhea-toolip-light">
    <div class="rhea-ultra-properties-one-wrapper owl-carousel" id="rhea-carousel-<?php echo esc_attr( $widget_id ); ?>">
		<?php
		while ( $properties_query->have_posts() ) {
			$properties_query->the_post();

            rhea_get_template_part( 'elementor/widgets/properties-widget/card-' . esc_html( $settings['card'] ) );
		}

		wp_reset_postdata();
		?>
    </div>
    <div id="rhea-nav-<?php echo esc_attr( $widget_id ); ?>" class="rhea-ultra-carousel-nav-center rhea-ultra-nav-box rhea-ultra-owl-nav owl-nav">
        <div id="rhea-dots-<?php echo esc_attr( $widget_id ); ?>" class="rhea-ultra-owl-dots owl-dots"></div>
    </div>
</section>
<script type="application/javascript">
    jQuery( document ).ready( function () {
        jQuery( "#rhea-carousel-<?php echo esc_attr( $widget_id ); ?>" ).owlCarousel( {
            items         : 3,
            nav           : true,
            dots          : true,
            dotsEach      : true,
            rtl           : rheaIsRTL(),
            smartSpeed    : 500,
            loop          : false,
            navText       : ['<i class="fas fa-caret-left"></i>', '<i class="fas fa-caret-right"></i>'],
            navContainer  : '#rhea-nav-<?php echo esc_attr( $widget_id ); ?>',
            dotsContainer : '#rhea-dots-<?php echo esc_attr( $widget_id ); ?>',
            responsive    : {
                // breakpoint from 0 up
                0 : {
                    items : 1
                },
                // breakpoint from 650 up
                650 : {
                    items  : 2,
                    margin : 20
                },
                // breakpoint from 1140 up
                1140 : {
                    items  : 3,
                    margin : 30
                },
                1400 : {
                    margin : 40
                }
            }
        } );
    } );
</script>