<?php
/**
 * Sidebar: Pages
 *
 * @package     realhomes
 * @subpackages classic
 */

$attached_sidebar = RealHomes_Custom_Sidebar::get_attached_sidebar( 'default-page-sidebar' );
if ( is_active_sidebar( $attached_sidebar ) ) : ?>
    <div class="span3 sidebar-wrap">
        <aside class="sidebar">
			<?php dynamic_sidebar( $attached_sidebar ); ?>
        </aside>
    </div>
<?php endif; ?>