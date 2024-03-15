<?php
inspiry_favorite_button( get_the_ID(), '', '', '/common/images/icons/ultra-favourite.svg', 'ultra' );
inspiry_add_to_compare_button();
$display_social_share         = get_option( 'theme_display_social_share', 'true' );
$inspiry_print_property_label = get_option( 'inspiry_print_property_label' );
$inspiry_share_property_label = get_option( 'inspiry_share_property_label' );

if ( $inspiry_print_property_label ) {
	$print_tooltip = esc_attr( $inspiry_print_property_label );
} else {
	$print_tooltip = esc_attr__( 'Print', 'framework' );
}
if ( $inspiry_share_property_label ) {
	$share_tooltip = esc_attr( $inspiry_share_property_label );
} else {
	$share_tooltip = esc_attr__( 'Share', 'framework' );
}

?>

<a href="javascript:window.print()" class="print rh-ui-tooltip" title="<?php echo esc_attr( $print_tooltip ); ?>">
	<?php inspiry_safe_include_svg( '/icons/print.svg' ); ?>
</a>
<div class="rh-ultra-share-wrapper">
	<?php if ( 'true' === $display_social_share ) : ?>
        <a href="#" class="rh-ultra-share share rh-ui-tooltip" title="<?php echo esc_attr( $share_tooltip ); ?>">
			<?php inspiry_safe_include_svg( '/icons/share.svg' ); ?>
        </a>
        <div class="share-this" data-check-mobile="<?php if ( wp_is_mobile() ) {
			echo esc_html( 'mobile' );
		} ?>" data-property-name="<?php the_title(); ?>" data-property-permalink="<?php the_permalink(); ?>"></div>
	<?php endif; ?>
</div>
