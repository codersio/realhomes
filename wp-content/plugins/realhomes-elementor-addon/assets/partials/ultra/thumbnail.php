<?php
global $settings;
$ere_property_grid_image = $settings['ere_property_grid_thumb_sizes'];
?>
<a class="rhea_permalink" href="<?php the_permalink() ?>">
	<?php
	if ( has_post_thumbnail( get_the_ID() ) ) {
		the_post_thumbnail( $ere_property_grid_image );
	} else {
		inspiry_image_placeholder( $ere_property_grid_image );
	}
	?>
</a>