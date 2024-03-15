<?php
$logo_path        = get_option( 'realhomes_frontend_dashboard_logo' );
$retina_logo_path = get_option( 'realhomes_frontend_dashboard_logo_retina' );
if ( ! empty( $logo_path ) || ! empty( $retina_logo_path ) ) {
	?>
    <div class="rh-logo">
        <a title="<?php bloginfo( 'name' ); ?>" href="<?php echo esc_url( realhomes_get_dashboard_page_url() ); ?>">
			<?php inspiry_logo_img( $logo_path, $retina_logo_path ); ?>
        </a>
    </div><!-- .rh-logo -->
	<?php
} else {
	?>
    <div class="rh-logo">
        <h2 class="rh-site-title">
            <a href="<?php echo esc_url( realhomes_get_dashboard_page_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
        </h2>
    </div><!-- .rh-logo -->
	<?php
}