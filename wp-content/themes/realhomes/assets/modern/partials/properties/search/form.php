<?php
/**
 * Properties Search Form
 *
 * @package    realhomes
 * @subpackage modern
 */

if ( inspiry_is_search_fields_configured() ) :
	$theme_search_fields = inspiry_get_search_fields();
	$theme_top_row_fields = get_option( 'inspiry_search_fields_main_row', '4' );
	?>
    <form class="rh_prop_search__form rh_prop_search_form_header advance-search-form" action="<?php echo esc_url( inspiry_get_search_page_url() ); ?>" method="get">

        <div class="rh_prop_search__fields">

			<?php

			if ( ! empty( $theme_search_fields ) && is_array( $theme_search_fields ) ) {
			?>
            <div class="rh_prop_search__wrap rh_prop_search_data" id="rh_fields_search__wrapper" data-top-bar="<?php echo esc_attr( $theme_top_row_fields ) ?>">
                <div class="rh_form_fat_top_fields rh_search_top_field_common">
					<?php
					foreach ( $theme_search_fields as $field ) {

						get_template_part( 'assets/modern/partials/properties/search/fields/' . $field );

					}
					do_action( 'inspiry_additional_search_fields' );
					?>
                </div>

                <div class="rh_form_fat_collapsed_fields_wrapper ">
                    <div class="rh_form_fat_collapsed_fields_container rh_search_fields_prepend_to">

                    </div>
					<?php

					}
					$feature_search_option = get_option( 'inspiry_search_fields_feature_search', 'true' );

					if ( ! empty( $feature_search_option ) && $feature_search_option == 'true' ) {
						get_template_part( 'assets/modern/partials/properties/search/fields/property-features' );
					}

					?>
                </div>
            </div>


        </div>
        <!-- /.rh_prop_search__fields -->

        <div class="rh_prop_search__buttons">
			<?php
			/**
			 * Search Button
			 */
			get_template_part( 'assets/modern/partials/properties/search/fields/button' );
			?>
        </div>
        <!-- /.rh_prop_search__buttons -->

    </form><!-- /.rh_prop_search__form -->

<?php
endif;