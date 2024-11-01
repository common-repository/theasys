<?php

/**
 * @link              theasys.io
 * @since             1.0.0
 * @package           Theasys
 *
 * @wordpress-plugin
 * Plugin Name:       Theasys
 * Plugin URI:        theasys.io
 * Description:       Theasys is a plugin that lets you easily embed your Theasys.io Tours to your Wordpress website.
 * Version:           1.0.0
 * Author:            Theasys
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       theasys
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
  die;
}

define( 'THEASYS_VERSION', '1.0.0' );

function activate_theasys() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-theasys-activator.php';
  Theasys_Activator::activate();
}

function deactivate_theasys() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-theasys-deactivator.php';
  Theasys_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_theasys' );
register_deactivation_hook( __FILE__, 'deactivate_theasys' );

require plugin_dir_path( __FILE__ ) . 'includes/class-theasys.php';

function run_theasys() {

  $plugin = new Theasys();
  $plugin->run();

}

run_theasys();
