<?php
/**
 * Field: Baths
 *
 * Baths field for advance property search.
 *
 * @since    3.0.0
 * @package realhomes_elementor_addon
 */
global $settings;
global $the_widget_id;

$search_fields_to_display = RHEA_Search_Form_Widget::rhea_search_select_sort();
if ( is_array($search_fields_to_display) && in_array( 'min-baths', $search_fields_to_display ) ) {

	$field_key = array_search( 'min-baths', $search_fields_to_display );

	$field_key = intval( $field_key ) + 1;

	$separator_class = '';
	if ( isset($settings['show_fields_separator']) && 'yes' === $settings['show_fields_separator'] ) {
		$separator_class = '  rhea-ultra-field-separator  ';
	}
	?>

    <div class="rhea_prop_search__option rhea_prop_search__select rhea_min_baths_field <?php echo esc_attr( $separator_class )?>"
         data-key-position ="<?php echo esc_attr( $field_key ); ?>"
         style="order: <?php echo esc_attr( $field_key ); ?>">

		<?php
		if ( 'yes' === $settings['show_labels'] ) {
			?>
            <label class="rhea_fields_labels" for="select-bathrooms-<?php echo esc_attr( $the_widget_id ); ?>">
				<?php
				if ( ! empty( $settings['min_bath_label'] ) ) {
					echo esc_html( $settings['min_bath_label'] );
				} else {
					esc_html_e( 'Bathroom', 'realhomes-elementor-addon' );
				}
				?>
            </label>
			<?php
		}
		?>

        <span class="rhea_prop_search__selectwrap">
		<select name="bathrooms" id="select-bathrooms-<?php echo esc_attr( $the_widget_id ); ?>"
                class="rhea_multi_select_picker show-tick"
                data-size="<?php echo esc_attr($settings['rhea_dropdown_items_in'] ); ?>"
        >
			<?php rhea_min_baths( $settings['min_bath_placeholder'], $settings['min_bath_drop_down_value'] ); ?>
		</select>
	</span>
    </div>
	<?php
}