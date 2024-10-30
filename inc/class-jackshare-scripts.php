<?php

	class JackShareScripts {

		/**
		 *
		 * Constructor. Load Scripts for JackShare plugin
		 *
		 */

		public function __construct() {

			add_action('wp_enqueue_scripts', array(&$this, "jackshare_load_scripts"));
		}

		function jackshare_load_scripts() {

			if ( ( is_singular() ) && ( is_main_query() ) ){

				$in_footer = true;

				//Load JS script
				wp_enqueue_script('jackshare-popup', plugin_dir_url( __FILE__ ) .'js/share.js', array(), '', $in_footer );

				//Load CSS script
				wp_enqueue_style('jackshare-styles', plugin_dir_url( __FILE__ ) .'css/jackshare-styles.css');
				wp_enqueue_style('jackshare-font', plugin_dir_url( __FILE__ ) .'css/font/jackshare-icons.css');

			}

		}

	}

?>