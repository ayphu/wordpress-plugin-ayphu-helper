<?php
/*
  Plugin Name: Ayphu Helper
  Plugin URI: https://ayphu.com
  Description: Funcionalidades a medida para sitios web en Ayphu.
  Version: 1.0.0
  Author: Ayphu
  Author URI: https://ayphu.com
  License: GPLv2 or later
  Text Domain: ayphu
*/

function ayphu_top_admin_bar_links() {
  global $wp_admin_bar;

  $wp_admin_bar->add_menu(
    [
      'id'    => 'ayphu_top_admin_bar_links',
      'title' => '<span class="dashicons-backup"></span> Cache',
      'href'  => '#'      
    ]
  );

  $links = [
    [
      'id'     => 'ayphu_clear_cache',
      'title'  => 'Purgar cache',
      'href'   => '#',
      'parent' => 'ayphu_top_admin_bar_links',
    ]
  ];

  foreach ($links as $link) {
    $wp_admin_bar->add_menu($link);
  }
}

function ayphu_css_and_js() {
  wp_enqueue_style('ayphu_css', plugins_url('style.css',__FILE__ ));
  wp_enqueue_script('ayphu_js', plugins_url('jquery.ajax.js',__FILE__ ));
}

function ayphu_ajax_clear_cache($returnvalue = false) {
  $api_cache = wp_remote_post( ' http://10.5.0.12/cache/', ['method' => 'POST']);
  if ($returnvalue) {
    $return_state = json_decode(wp_remote_retrieve_body($api_cache));
    wp_send_json($return_state);
  }
}

function ayphu_clear_cache_swith_theme () {
  ayphu_ajax_clear_cache();
}

function ayphu_load_plugin() {
  if ('1' === get_option('ayphu_activated')) {
    return;
  }

  ayphu_ajax_clear_cache();

  update_option('ayphu_activated', '1');
}

function ayphu_admin_notice__success() { ?>
  <div id="ayphu-admin-notice"></div>
  <?php
}

if (is_admin()) {
  require 'plugin-update-checker-4.13/plugin-update-checker.php';
  $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/ayphu/wordpress-plugin-ayphu-helper.git',
    __FILE__,
    'ayphu'
  );

  //Set the branch that contains the stable release.
  $myUpdateChecker->getVcsApi()->enableReleaseAssets();

  add_action('wp_before_admin_bar_render', 'ayphu_top_admin_bar_links');
  add_action('admin_enqueue_scripts', 'ayphu_css_and_js');
  add_action( 'wp_ajax_clearCache', 'ayphu_ajax_clear_cache');
  add_action( 'wp_ajax_nopriv_clearCache', 'ayphu_ajax_clear_cache');
  add_action( 'admin_notices', 'ayphu_admin_notice__success');
  add_action('switch_theme', 'ayphu_clear_cache_swith_theme');
  add_action( 'init', 'ayphu_load_plugin' );
}