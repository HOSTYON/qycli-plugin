<?php
/**
 * @package qycli-pma
 * @version 0.1.0
 */
/*
Plugin Name: qycli Utilities
Description: Simple Plugin to display PMA as an admin submenu.
Author: Micha Cassola
Author URI: https://github.com/michacassola
Version: 0.1.0
License: MIT http://opensource.org/licenses/MIT
*/

add_action( 'admin_menu', 'qyc_pma_menu' );

add_action("admin_menu", "qyc_pma_submenu");
function qyc_pma_submenu() {
  add_submenu_page(
        'tools.php',
        'qycli PMA',
        '<div class="dashicons dashicons-database"></div> qycli PMA',
        'administrator',
        'qyc-pma',
        'qyc_pma_admin_page',
  		1 );
}

function qyc_pma_admin_page() {
	?>
	<div style="z-index: 10; margin: 0 -20px -70px -20px; width: calc(100% + 20px); height: calc(100vh - 32px);">
		<iframe width="100%" height="100%" src="/qyc-pma/"></iframe>
	</div>
    <?php
}

function remove_footer_admin() {
echo '';
}
function remove_footer_admin_ver() {
return '';
}
 
add_filter('admin_footer_text', 'remove_footer_admin');
add_filter('update_footer', 'remove_footer_admin_ver', 999);