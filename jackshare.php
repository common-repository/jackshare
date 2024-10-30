<?php

/**
* Plugin Name: Jackshare
* Plugin URI: https://wordpress.org/plugins/jackshare
* Description: Super simple Social media sharing buttons with minimal design and lightning fast performance.
* Version: 2.1.10
* License: GPL v3
* License URI: http://www.gnu.org/licenses/gpl-3.0.txt
* Text Domain: jackshare
* Domain Path: /languages
* Author: Iakovos Frountas
* Author URI: https://frountas.com
*/


if ( ! defined( 'ABSPATH' ) ) {
    echo 'This file should not be accessed directly!';
    exit; // Exit if accessed directly
}

//Plugin name and version
define( 'JACKSHARE_PLUGIN_NAME', 'Jackshare');
define( 'JACKSHARE_PLUGIN_VERSION', 'v2.1.10');

define( 'JACKSHARE_PLUGIN_FILE', __FILE__ );
define( 'JACKSHARE_PLUGIN_DIR', dirname(JACKSHARE_PLUGIN_FILE) . '/' );


class JackShareSetup {


	/**
	 * Constructor
	 */

 	public function __construct() {

 		//Load localisation files
    	add_action("plugins_loaded", array(&$this, "jackshare_text_domain"));


	 	// Controlling all the JS and CSS scripts are needed for this plugin
    	include_once JACKSHARE_PLUGIN_DIR . 'inc/class-jackshare-scripts.php';
    	new JackShareScripts();

    	// Load all the plugin functionality
		include_once JACKSHARE_PLUGIN_DIR . 'inc/class-jackshare-admin-page.php';
		new JackShareAdminPage();

		// The plugin WordPress admin page
		include_once JACKSHARE_PLUGIN_DIR . 'inc/class-jackshare-functions.php';
		new JackShareFunctions();
 	}

 	/**
     * This function loads the text domain
     */
    function jackshare_text_domain() {
        load_plugin_textdomain('jackshare', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }




}

new JackShareSetup();
