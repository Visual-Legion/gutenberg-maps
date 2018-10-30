<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://visual-legion.com
 * @since             0.0.1
 * @package           Gutenberg_Maps
 *
 * @wordpress-plugin
 * Plugin Name:       Gutenberg Maps
 * Plugin URI:        https://github.com/Visual-Legion/gutenberg-maps.git
 * Description:       This plugin makes it easy to integrate google maps in the gutenberg way
 * Version:           0.0.1
 * Author:            Ulysse Coates
 * Author URI:        https://visual-legion.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gutenberg-maps
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GUTENBERG_MAPS_VERSION', '0.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gutenberg-maps-activator.php
 */
function activate_gutenberg_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gutenberg-maps-activator.php';
	Gutenberg_Maps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gutenberg-maps-deactivator.php
 */
function deactivate_gutenberg_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gutenberg-maps-deactivator.php';
	Gutenberg_Maps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gutenberg_maps' );
register_deactivation_hook( __FILE__, 'deactivate_gutenberg_maps' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gutenberg-maps.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */



/**
* Settings Page
*/

require_once 'settings.php';

/**
/* Maps 
*/

// To create if php render is necessary
//require_once 'php-renders/gutenberg-maps-render.php';

function vl_gm_my_register_main() {

  // Register our block script with WordPress
  wp_register_script(
    'vl-gm-js',
    plugins_url('/blocks/dist/blocks.build.js', __FILE__),
    array('wp-blocks', 'wp-element')
  );

  // Adding settings value from db to block js
  if (isset(get_option('vl_gm_options')['vl_gm_google_maps_api_key'])) {
    wp_localize_script( 'vl-gm-js', 'api_key', get_option('vl_gm_options')['vl_gm_google_maps_api_key'] );
  } else {
    wp_localize_script( 'vl-gm-js', 'api_key', null );
  }

  // Register our block's base CSS  
  wp_register_style(
    'vl-gm-style',
    plugins_url( '/blocks/dist/blocks.style.build.css', __FILE__ ),
    array( 'wp-blocks' )
  );
  
  // Register our block's editor-specific CSS
  wp_register_style(
    'vl-gm-edit-style',
    plugins_url('/blocks/dist/blocks.editor.build.css', __FILE__),
    array( 'wp-edit-blocks' )
  );  
  
  // Enqueue the script in the editor
  register_block_type('vl-gm/main', array( 
  	// 'render_callback' => 'vl_blocks_main_callback',
    'editor_script' => 'vl-gm-js',
    'editor_style' => 'vl-gm-edit-style',
    'style' => 'vl-gm-style'
  ));
}

add_action('init', 'vl_gm_my_register_main');

function run_gutenberg_maps() {

	$plugin = new Gutenberg_Maps();
	$plugin->run();

}
run_gutenberg_maps();
