<?php
/**
 * Field: Property Lot Size
 *
 * Lot Size field for advance property search.
 *
 * @since    3.19.0
 * @package realhomes_elementor_addon
 */
global $the_widget_id;
global $settings;

$search_fields_to_display = RHEA_Search_Form_Widget::rhea_search_select_sort();
if ( is_array($search_fields_to_display) && in_array( 'min-max-lot-size', $search_fields_to_display ) ) {

	$field_key = array_search( 'min-max-lot-size', $search_fields_to_display );

	$field_key = intval( $field_key ) + 1;

	$separator_class = '';
	if ( isset($settings['show_fields_separator']) && 'yes' === $settings['show_fields_separator'] ) {
		$separator_class = '  rhea-ultra-field-separator  ';
	}
	?>
    <div class="rhea_prop_search__option rhea_mod_text_field rhea_min_lot_size_field <?php echo esc_attr( $separator_class ) ?>"
         data-key-position ="<?php echo esc_attr( $field_key ); ?>"
         style="order: <?php echo esc_attr( $field_key ); ?>">

		<?php
		if ( 'yes' === $settings['show_labels'] ) {

			?>
            <label class="rhea_fields_labels" for="min-lot-size-<?php echo esc_attr( $the_widget_id ); ?>">

		<span class="label">
			<?php

			if ( ! empty( $settings['min_lot_size_label'] ) ) {
				echo esc_html( $settings['min_lot_size_label'] );
			} else {
				esc_html_e( 'Min Lot Size', 'realhomes-elementor-addon' );
			}
			?>
	    </span>

				<?php
				if ( ! empty( $settings['lot_size_units_placeholder'] ) ) {
					?>
                    <span class="unit">
                <?php
                echo esc_html( $settings['lot_size_units_placeholder'] );
                ?>
                 </span>
					<?php

				}

				?>

            </label>
			<?php
		}
		?>
        <span class="rhea-text-field-wrapper">
        <input type="text" autocomplete="off" name="min-lot-size" id="min-lot-size-<?php echo esc_attr( $the_widget_id ); ?>"
               pattern="[0-9]+"
               value="<?php echo isset( $_GET['min-lot-size'] ) ? esc_attr( $_GET['min-lot-size'] ) : ''; ?>"
               placeholder="<?php echo esc_attr( $settings['min_lot_size_placeholder'] ); ?>"

			<?php if ( ! empty( $settings['lot_size_units_title_attr'] ) ) { ?>
                title="<?php echo esc_attr( $settings['lot_size_units_title_attr'] ); ?>"
			<?php } ?>
        />
        </span>
    </div>

    <div class="rhea_prop_search__option rhea_mod_text_field rhea_max_lot_size_field <?php echo esc_attr( $separator_class ) ?>"
         data-key-position ="<?php echo esc_attr( $field_key + .1 ); ?>"
         style="order: <?php echo esc_attr( $field_key ); ?>">
		<?php
		if ( 'yes' === $settings['show_labels'] ) {
			?>
            <label class="rhea_fields_labels" for="max-lot-size-<?php echo esc_attr( $the_widget_id ); ?>">
		<span class="label">
			<?php
			if ( ! empty( $settings['max_lot_size_label'] ) ) {
				echo esc_html( $settings['max_lot_size_label'] );
			} else {
				esc_html_e( 'Max Lot Size', 'realhomes-elementor-addon' );
			}
			?>
	    </span>
				<?php
				if ( ! empty( $settings['lot_size_units_placeholder'] ) ) {
					?>
                    <span class="unit">
                <?php
                echo esc_html( $settings['lot_size_units_placeholder'] );
                ?>
                 </span>
					<?php
				}
				?>
            </label>
			<?php
		}
		?>
        <span class="rhea-text-field-wrapper">
        <input type="text" autocomplete="off" name="max-lot-size" id="max-lot-size-<?php echo esc_attr( $the_widget_id ); ?>"
               pattern="[0-9]+"
               value="<?php echo isset( $_GET['max-lot-size'] ) ? esc_attr( $_GET['max-lot-size'] ) : ''; ?>"
               placeholder="<?php echo esc_attr( $settings['max_lot_size_placeholder'] ); ?>"
			<?php if ( ! empty( $settings['lot_size_units_title_attr'] ) ) { ?>
                title="<?php echo esc_attr( $settings['lot_size_units_title_attr'] ); ?>"
			<?php } ?>
        />
        </span>
    </div>

	<?php
}