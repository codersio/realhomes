<?php
/**
 * Field: Check-In & Check-Out
 *
 * Check-In & Check-Out field for advance property search.
 * @package realhomes-elementor-addon
 *
 * @since 2.1.1
 */

global $the_widget_id;
global $settings;

$search_fields_to_display = RHEA_Search_Form_Widget::rhea_search_select_sort();

if ( is_array($search_fields_to_display) && in_array( 'check-in-out', $search_fields_to_display ) ) {

	$field_key = array_search( 'check-in-out', $search_fields_to_display );

	$field_key = intval( $field_key ) + 1;

	$separator_class = '';
	if ( isset( $settings['show_fields_separator'] ) && 'yes' === $settings['show_fields_separator'] ) {
		$separator_class = '  rhea-ultra-field-separator  ';
	}
    ?>
    <div class="rhea_prop_search__option rhea_mod_text_field rvr_check_in rh_mod_text_field <?php echo esc_attr( $separator_class ); ?>" style="order: <?php echo esc_attr( $field_key ); ?>" data-key-position ="<?php echo esc_attr( $field_key ); ?>">
	    <?php
	    if ( 'yes' === $settings['show_labels'] ) {
		    ?>
            <label class="rhea_fields_labels" for="select-status-<?php echo esc_attr( $the_widget_id ); ?>">
			    <?php
			    if ( ! empty( $settings['check_in_label'] ) ) {
				    echo esc_html( $settings['check_in_label'] );
			    } else {
				    echo esc_html__( 'Check In', 'realhomes-elementor-addon' );
			    }
			    ?>
            </label>
		    <?php
	    }

        $checkin_placeholder_text = $settings[ 'check_in_placeholder' ];
        if ( empty( $checkin_placeholder_text ) ) {
            $checkin_placeholder_text = rh_any_text();
        }
        ?>
        <span class="rhea-text-field-wrapper">
            <input type="text" name="check-in" id="rhea-check-in-search" value="<?php echo ! empty( $_GET['check-in'] ) ? esc_attr( $_GET['check-in'] ) : ''; ?>" placeholder="<?php echo esc_attr( $checkin_placeholder_text ); ?>" autocomplete="off" />
        </span>
    </div>

    <div class="rhea_prop_search__option rhea_mod_text_field rvr_check_out rh_mod_text_field <?php echo esc_attr( $separator_class ); ?>" style="order: <?php echo esc_attr( $field_key ); ?>" data-key-position ="<?php echo esc_attr( $field_key ); ?>">
        <?php
        if ( 'yes' === $settings['show_labels'] ) {
	        ?>
            <label class="rhea_fields_labels" for="select-status-<?php echo esc_attr( $the_widget_id ); ?>">
		        <?php
		        if ( ! empty( $settings['check_out_label'] ) ) {
			        echo esc_html( $settings['check_out_label'] );
		        } else {
			        echo esc_html__( 'Check Out', 'realhomes-elementor-addon' );
		        }
		        ?>
            </label>
	        <?php
        }

        $checkout_placeholder_text = $settings[ 'check_out_placeholder' ];
        if ( empty( $checkout_placeholder_text ) ) {
            $checkout_placeholder_text = rh_any_text();
        }
        ?>
        <span class="rhea-text-field-wrapper">
            <input type="text" name="check-out" id="rhea-check-out-search" value="<?php echo ! empty( $_GET['check-out'] ) ? esc_attr( $_GET['check-out'] ) : ''; ?>" placeholder="<?php echo esc_attr( $checkout_placeholder_text ); ?>" autocomplete="off" />
        </span>
    </div>
    <?php
}
?>
