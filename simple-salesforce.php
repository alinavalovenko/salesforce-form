<?php
/*
	Plugin Name: Simple Salesforce Form Plugin
	Version: 1.0
	Author: Alina Valovenko
	Author URI: http://www.valovenko.pro
	License: GPL2
*/

if ( ! class_exists( 'Simple_Salesforce_Form' ) ) {

	class Simple_Salesforce_Form {
		public function __construct() {
			register_activation_hook( plugin_basename( __FILE__ ), array( $this, 'ssf_activate' ) );
			register_deactivation_hook( plugin_basename( __FILE__ ), array( $this, 'ssf_deactivate' ) );
			register_uninstall_hook( plugin_basename( __FILE__ ), array( $this, 'ssf_uninstall' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'ssf_enqueue_scripts' ) );

			add_shortcode( 'salesforce', 'salesforce_form_shortcode_render' );

		}

		public function ssf_activate() {
			return true;
		}

		public function ssf_deactivate() {
			return true;
		}

		public function ssf_uninstall() {
			return true;
		}

		/***
		 * Add styles and scripts to plugin functionality
		 */
		public function ssf_enqueue_scripts() {
			wp_enqueue_style( 'ssf-style', plugin_dir_url( __FILE__ ) . 'assets/styles.css' );
			wp_enqueue_script( 'ssf-jquery', 'https://code.jquery.com/jquery-3.3.1.min.js', '', '1.0.0', true );
			wp_enqueue_script( 'ssf-scripts', plugin_dir_url( __FILE__ ) . 'assets/scripts.js', array( 'ssf-jquery' ), '1.0.0', true );
		}

		/***
		 * [salesforce] shortcode main handler
		 *
		 * @param array $atts
		 */
		function salesforce_form_shortcode_render( $atts ) {
			$atts = shortcode_atts( array(
				'action' => '#',
				'oid' => '#',
				'retUrl'    => '#',
			), $atts, 'salesforce' );
			echo 'Test';
		}
	}
}