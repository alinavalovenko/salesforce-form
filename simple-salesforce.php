<?php
/*
	Plugin Name: Simple Salesforce Form Plugin
	Description: Short-code with salesforce form
	Version: 1.0
	Author: Alina Valovenko
	Author URI: http://www.valovenko.pro
	License: GPL2
*/

if ( ! class_exists( 'Simple_Salesforce_Form' ) ) {

	if ( ! defined( 'SSF_TEMP' ) ) {
		define( 'SSF_TEMP', plugin_dir_path( __FILE__ ) . 'temp' . DIRECTORY_SEPARATOR );
	}

	class Simple_Salesforce_Form {
		public function __construct() {
			register_activation_hook( plugin_basename( __FILE__ ), array( $this, 'ssf_activate' ) );
			register_deactivation_hook( plugin_basename( __FILE__ ), array( $this, 'ssf_deactivate' ) );
			register_uninstall_hook( plugin_basename( __FILE__ ), 'ssf_uninstall' );

			add_action( 'wp_enqueue_scripts', array( $this, 'ssf_enqueue_scripts' ) );

			add_shortcode( 'salesforce', array( $this, 'salesforce_form_shortcode_render' ) );

		}

		public function ssf_activate() {
			return true;
		}

		public function ssf_deactivate() {
			return true;
		}

		public function ssf_uninstall() {
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
		 * @param $atts
		 *
		 * @return string
		 */
		function salesforce_form_shortcode_render( $atts ) {
			$output = '';
			$atts   = shortcode_atts( array(
				'action' => '#',
				'oid'    => '#',
				'returl' => '#',
			), $atts );

			$form_content = $this->get_salesforce_form_html( $atts['action'], $atts['oid'], $atts['returl'] );
			ob_start();
			echo $form_content;
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}

		/***
		 * @param string $action
		 * @param string $oid
		 * @param string $retUrl
		 *
		 * @return false|string
		 */
		public function get_salesforce_form_html( $action = '#', $oid = '#', $retUrl = '#' ) {
			$action_pattern = '/action=\"(.*?)\"/';
			$oid_pattern    = '/name="oid"[\s]*value=\"(.*?)\"/';
			$retUrl_pattern = '/name="retURL"[\s]*value=\"(.*?)\"/';

			$form = file_get_contents( SSF_TEMP . 'form.txt' ) or die( 'Unable to open file ' . SSF_TEMP . 'form.txt' );

			if ( '#' !== $action ) {
				preg_match_all( $action_pattern, $form, $action_matches );
				$form = str_replace( $action_matches[1][0], $action, $form );
			}

			if ( '#' !== $oid ) {
				preg_match_all( $oid_pattern, $form, $oid_matches );
				$form = str_replace( $oid_matches[1][0], $oid, $form );
			}

			if ( '#' !== $retUrl ) {
				preg_match_all( $retUrl_pattern, $form, $retUrl_matches );
				$form = str_replace( $retUrl_matches[1][0], $retUrl, $form );
			}


			return $form;
		}
	}

	new Simple_Salesforce_Form();
}