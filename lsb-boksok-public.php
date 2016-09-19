<?php
/**
 * Plugin Name: Boksøk: Søkeskjema-widget
 * Description: Widget som lar besøkende søke direkte i Boksøk (boksok.no).
 * Text Domain: lsb-boksok-public
 * Domain Path: /language
 * Author: Lilly Labs
 * Author URI: http://lillylabs.no
 * Version: 1.0
 */

include('class-boksok-search-form.php');

class LSB_Boksok_Widgets {

	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
    add_action( 'widgets_init', function(){
	   register_widget( 'LSB_Boksok_Search_Widget' );
    });
  }

	function load_text_domain() {
		load_plugin_textdomain( 'lsb-boksok-widgets', false, plugin_basename( dirname( __FILE__ ) ) );
	}
}

new LSB_Boksok_Widgets();
