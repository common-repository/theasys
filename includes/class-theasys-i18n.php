<?php

/**
 * @since      1.0.0
 * @package    Theasys
 * @subpackage Theasys/includes
 * @author     Theasys <dev@theasys.io>
 */
class Theasys_i18n {

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {

    load_plugin_textdomain(
      'theasys',
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );

  }

}
