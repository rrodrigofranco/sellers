<?php
// Settings menu creation
function check_sellers_admin_menu() {
    add_menu_page( 'Sellers', 'Sellers','manage_options', SCBW_ROUTE . '/admin/config.php', '', 'dashicons-admin-comments');
}
add_action( 'admin_menu', 'check_sellers_admin_menu' );