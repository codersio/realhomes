<?php
/**
 * Contains the property gallery images carousel for single property gallery fullwidth variation.
 *
 * @since 3.20.0
 * @package    realhomes
 * @subpackage modern
 */

$properties_images = rwmb_meta( 'REAL_HOMES_property_images', 'type=plupload_image&size=' . 'post-featured-image', get_the_ID() );
$prop_detail_login = inspiry_prop_detail_login();

if ( ! empty( $properties_images ) && 1 < count( $properties_images ) && ( 'yes' != $prop_detail_login || is_user_logged_in() ) ) { ?>
	<?php
	get_template_part( 'assets/modern/partials/property/gallery/carousel', null, array(
		'class'    => 'inspiry_property_fw_carousel_style',
		'count'    => count( $properties_images ),
		'gallery'  => $properties_images,
		'lightbox' => get_option( 'inspiry_display_title_in_lightbox' ),
	) );

	if ( has_post_thumbnail() ) {
		?>
        <div id="property-featured-image" class="clearfix only-for-print">
			<?php
			$image_id  = get_post_thumbnail_id();
			$image_url = wp_get_attachment_url( $image_id );
			echo '<img src="' . esc_url( $image_url ) . '" alt="' . the_title_attribute( 'echo=0' ) . '" />';
			?>
        </div>
		<?php
	};
} elseif ( has_post_thumbnail() ) {
	?>
    <div class="rh_section rh_wrap--padding rh_wrap--topPadding">
        <div id="property-featured-image" class="clearfix">
			<?php
			$image_id  = get_post_thumbnail_id();
			$image_url = wp_get_attachment_url( $image_id );
			echo '<a href="' . esc_url( $image_url ) . '" data-fancybox="gallery">';
			echo '<img src="' . esc_url( $image_url ) . '" alt="' . the_title_attribute( 'echo=0' ) . '" />';
			echo '</a>';
			?>
        </div>
    </div>
	<?php
}