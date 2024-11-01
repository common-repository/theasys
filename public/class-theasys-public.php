<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Theasys
 * @subpackage Theasys/public
 * @author     Theasys <dev@theasys.io>
 */
class Theasys_Public {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    add_shortcode( 'theasys_embed', array($this,'theasys_embed_shortcode') );

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

  }

  /**
   * Add Shortcode
   *
   * @since    1.0.0
   */
  public function theasys_embed_shortcode( $atts ) {

    // Attributes
    $atts = shortcode_atts(
      array(
        'key' => '',
        'width' => '',
        'height' => '',
      ),
      $atts,
      'theasys_embed'
    );

    $options = get_option('theasys');

    $key = isset( $atts['key'] ) ? $atts['key'] : '';

    $html = '';

    if( isset( $options['embed_js'] ) && $options['embed_js'] !== '' && $key !== '' ){

      $width = (int)$atts['width'];
      $height = (int)$atts['height'];

      $params = '';

      if( $width > 0 ){

        $params .= ' data-width="'.$width.'"';

      }

      if( $height > 0 ){

        $params .= ' data-height="'.$height.'" ';

      }

      $html .= '<script async src="'.$options['embed_js'].'" data-theasys="'.$key.'"'.$params.'></script>';

    }

    return $html;

  }

}
