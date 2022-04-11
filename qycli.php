<?php
/**
 * @package qycli-utilities
 * @version 1.0.0
 */
/*
Plugin Name: qycli Utilities
Description: qycli Utilities
Author: Micha Cassola
Author URI: https://github.com/michacassola
Version: 1.0.0
License: MIT http://opensource.org/licenses/MIT
*/

add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );

add_action("admin_menu", "qyc_pma_submenu");
function qyc_pma_submenu() {
  add_submenu_page(
        'tools.php',
        'qycli Utilities',
        'qycli Utilities',
        'administrator',
        'qycli-utilities',
        'qyc_admin_page',
  		-1 );
}

function qyc_admin_page() {
	?>
	<div class="wrap">
		<h2 style="margin-bottom: 15px;">qycli Utilities</h2>
		<a href="https://<?php echo DB_USER ?>:<?php echo DB_PASSWORD ?>@<?php echo $_SERVER['HTTP_HOST'] ?>/qyc-pma/" target="_blank"><div class="submit button button-primary">
			phpMyAdmin <div class="dashicons dashicons-external" style="position: relative; bottom: 2px; vertical-align: middle;"></div></div></a>
		<h3 style="margin: 30px 0 15px 0;">WP redis Object Caching Statistics</h3>
		<?php $GLOBALS['wp_object_cache']->stats(); ?>
	</div>
    <?php
}

if ( function_exists( 'wp_cache_flush' ) ) {
    add_action( 'admin_bar_menu', 'o1_flush_cache_button', 100 );
}

/* WP Redis Object Cache Flush Admin Bar Button*/
function o1_flush_cache_button( $wp_admin_bar ) {

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_GET['flush-cache-button'] )
        && 'flush' === $_GET['flush-cache-button']
        && wp_verify_nonce( $_GET['_wpnonce'], 'flush-cache-button' )
    ) {
        wp_cache_flush();
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-success is-dismissible"><p>Object Cache flushed.</p></div>';
        } );
    }

    $dashboard_url = admin_url( add_query_arg( 'flush-cache-button', 'flush', 'index.php' ) );
    $args = array(
        'id'    => 'flush_cache_button',
        'title' => 'Flush Object Cache',
        'href'  => wp_nonce_url( $dashboard_url, 'flush-cache-button' ),
        'meta'  => array( 'class' => 'flush-cache-button' )
    );
    $wp_admin_bar->add_node( $args );
}
