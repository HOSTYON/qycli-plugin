<?php
/**
 * @package qycli-utilities
 * @version 1.0.1
 */
/*
Plugin Name: qycli Utilities
Description: qycli Utilities
Author: Micha Cassola
Author URI: https://github.com/michacassola
Version: 1.0.1
License: MIT http://opensource.org/licenses/MIT
*/

add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );

add_action("admin_menu", "qyc_submenu");
function qyc_submenu() {
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
	//Get the active tab from the $_GET param
  $default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
  
  ?>
	<div class="wrap">
		<h2 style="margin-bottom: 15px;">qycli Utilities</h2>

    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
      <a href="?page=qycli-utilities" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">phpMyAdmin Login</a>
      <a href="?page=qycli-utilities&tab=filemanager" class="nav-tab <?php if($tab==='filemanager'):?>nav-tab-active<?php endif; ?>">Filemanager</a>
      <a href="?page=qycli-utilities&tab=object-stats" class="nav-tab <?php if($tab==='object-stats'):?>nav-tab-active<?php endif; ?>">Object Cache Stats</a>
      
    </nav>

    <div class="tab-content">
    <?php switch($tab) :
      case 'object-stats':
        qyc_object_stats_tab();
        break;
      case 'filemanager':
        qyc_filemanager_tab();
        break;
      default:
        qyc_default_tab();
        break;
    endswitch; ?>
    </div>

  </div>
    <?php
}

function qyc_default_tab() {
  ?>
    <br><p>Login to phpMyAdmin with:</p>
    <p>User: <?php echo DB_USER ?> <br>
      Password: <?php echo DB_PASSWORD ?></p>
		<a href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/qyc-pma/" target="_blank"><div class="submit button button-primary">
			phpMyAdmin <div class="dashicons dashicons-external" style="position: relative; bottom: 2px; vertical-align: middle;"></div></div></a>
  <?php
}

function qyc_object_stats_tab() {
  ?>
  <h3 style="margin: 30px 0 15px 0;">WP redis Object Caching Statistics</h3>
		<?php $GLOBALS['wp_object_cache']->stats(); ?>
  <?php
}

function qyc_filemanager_tab() {

    define('FM_EMBED', true);
    define('FM_SELF_URL', UrlHelper::currentUrl());
    require 'qycli-plugin/tinyfilemanager/tinyfilemanager.php';

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
