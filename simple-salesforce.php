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
			add_action( 'admin_menu', array( $this, 'ssf_add_admin_page' ) );

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
		 * Create Plugin Page for Plugin
		 */
		public function ssf_add_admin_page() {
			add_submenu_page( 'tools.php', 'Simple Salesforce Form', 'Salesforce Form', 'manage_options', 'simple-salesforce-form', array(
				$this,
				'ssf_create_admin_page'
			) );
		}

		/***
		 * Add ability to set up salesforce form in wp admin panel
		 */
		public function ssf_create_admin_page() {
			if ( isset( $_POST['save-ssf-form'] ) ) {
				$form_content = $_FILES['ssf-content'];
				if ( ! empty( $form_content ) ) {
					$result = move_uploaded_file( $form_content["tmp_name"], SSF_TEMP . 'form.txt' );
					if ( ! $result ) {
						echo 'Something went wrong, please check permissions of the file ' . SSF_TEMP . 'form.txt or contact with a developer';
					}
				}
			}
			require_once 'dashboard.php';
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

			$form_content = $this->replace_form_parameters( $atts['action'], $atts['oid'], $atts['returl'] );
			$form         = $this->set_up_dropdown_lists( $form_content );
			ob_start();
			echo $form;
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}

		/***
		 * Replace form fields value with shortcode parameters
		 *
		 * @param string $action
		 * @param string $oid
		 * @param string $retUrl
		 *
		 * @return false|string
		 */
		public function replace_form_parameters( $action = '#', $oid = '#', $retUrl = '#' ) {
			$action_pattern = '/action=\"(.*?)\"/';
			$oid_pattern    = '/name="oid"[\s]*value=\"(.*?)\"/';
			$retUrl_pattern = '/name="retURL"[\s]*value=\"(.*?)\"/';

			$form = file_get_contents( SSF_TEMP . 'form.txt' ) or die( 'Unable to open file ' . SSF_TEMP . 'form.txt' );
			if ( ! empty( trim( $form ) ) ) {
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
				$form = str_replace( '&#39;', '\'', $form ); // replace utf symbols
				$form = str_replace( 'id="state_code"', 'id="state_code_parent"', $form ); // replace utf symbols
				$form = str_replace( 'name="state_code"', 'name="state_code_parent"', $form ); // replace utf symbols

				return $form;
			} else
				return false;
		}

		/***
		 * Add class to states list options to identify country
		 *
		 * @param $form
		 *
		 * @return mixed
		 */
		public function set_up_dropdown_lists( $form ) {
			$csv = array_map( 'str_getcsv', file( SSF_TEMP . 'CountryStateMetadata.csv' ) );
			foreach ( $csv as $item ) {
				$state_code = $item[3];
				if ( 'State Code' !== $state_code ) {
					$state_name     = $item[2];
					$country_code   = $item[1];
					$option_pattern = "/<option[\s+]value=\"$state_code\">$state_name<\/option>/";
					preg_match_all( $option_pattern, $form, $matches );
					$new_option = '<option value="' . $state_code . '" class="' . $country_code . '">' . $state_name . '</option>';
					if ( isset( $matches[0][0] ) && ! empty( $matches[0][0] ) ) {
						$form = str_replace( $matches[0][0], $new_option, $form );
					} else {
						continue;
					}
				} else {
					continue;
				}

			}

			return $form;
		}
	}

	new Simple_Salesforce_Form();
}