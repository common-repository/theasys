<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Theasys
 * @subpackage Theasys/admin
 * @author     Theasys <dev@theasys.io>
 */
class Theasys_Admin {

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
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    $this->options = get_option('theasys');

    if(!$this->options){

      $this->options = array(
      'api_key' => '',// DO NOT CHANGE THIS
      'api_url' => 'https://api.theasys.io/',// DO NOT CHANGE THIS
      'website_url' => 'https://www.theasys.io/',// DO NOT CHANGE THIS
      'website_static_url' => 'https://www.theasys.io/static/',// DO NOT CHANGE THIS
      'embed_js' => 'https://www.theasys.io/static/embed.js',// DO NOT CHANGE THIS
      'host' => 'https://api.theasys.io',// DO NOT CHANGE THIS
      );

      add_option( 'theasys', $this->options );

    }

  }

  public function theasys_box() {

    add_meta_box('theasys','<img src="'.plugins_url( 'admin/images/icon_logo.png', dirname(__FILE__) ).'" alt="'.$this->plugin_name.' logo" height="12">&nbsp;'.__('Theasys'),array($this,'theasys_box_content'),'post','side','default');

    add_meta_box('theasys','<img src="'.plugins_url( 'admin/images/icon_logo.png', dirname(__FILE__) ).'" alt="'.$this->plugin_name.' logo" height="12">&nbsp;'.__('Theasys'),array($this,'theasys_box_content'),'page','side','default');

  }

  public function theasys_box_content() {

    $html = '
      <div id="theasys_embed_dialog" class="hidden">
        <div class="form-group">
          <label><strong>'.__('Step 1:','theasys').'</strong> '.__('Select size.','theasys').'</label>
          <select id="embed_size_select">
            <option value="0" data-width="560" data-height="315">560 x 315</option>
            <option value="3" data-width="640" data-height="360">640 x 360</option>
            <option value="2" data-width="853" data-height="480">853 x 480</option>
            <option value="1" data-width="1280" data-height="720">1280 x 720</option>
            <option value="c" data-width="0" data-height="0">'.__('Custom responsive size','theasys').'</option>
          </select>
          <div id="custom_width_height_inputs_wrapper" class="hidden">
            <label>Width: <input data-type="w" type="number" min="0" value="0"></label> <label>Height: <input data-type="h" type="number" min="0" value="0"></label>
              <ul>
                <li>'.__('<strong>Responsive:</strong> set a "Height" value and leave "Width" at zero.','theasys').'</li>
                <li>'.__('<strong>Custom size:</strong> set "Width" and "Height" values.','theasys').'</li>
                <li>'.__('<strong>Auto Width &amp; auto Height:</strong>. set values at zero.','theasys').'</li>
              </ul>
          </div>
        </div>
        <div class="form-group spacer10">
          <div class="embed_code_textarea_wrapper">
            <label><strong>'.__('Step 2:','theasys').'</strong> '.__('Copy &amp; paste the code below, inside the post textarea. You can place it more than one time.','theasys').'</label>
            <textarea id="embed_code_textarea" rows="3" onclick="this.focus(); this.select();"></textarea>
            <p><input id="embed_code_preview" type="button" value="'.__('Preview','theasys').'" class="button"></p>
          </div>
        </div>
      </div>
      <div id="theasys_embed_preview_dialog" class="hidden">
        <div class="ui-dialog-content">
          <div class="embed_code_preview_wrapper"></div>
        </div>
      </div>
      <p>
        <input type="text" id="theasys_search_input" name="theasys_search_input" class="search_in_theasys form-input-tip" size="16" autocomplete="off" value="" placeholder="'.__('type a tour name','theasys').'">
        <input id="theasys_search_button" type="button" class="button" value="'.__('Search','theasys').'">
      </p>
      <input type="hidden" id="theasys_api_key" value="'.$this->options['api_key'].'">
      <input type="hidden" id="theasys_api_url" value="'.$this->options['api_url'].'">
      <input type="hidden" id="theasys_website_url" value="'.$this->options['website_url'].'">
      <input type="hidden" id="theasys_embed_js" value="'.$this->options['embed_js'].'">
      <input type="hidden" id="theasys_loading_img" value="'.plugins_url( 'admin/images/loading.gif', dirname(__FILE__) ).'">
      <input type="hidden" id="theasys_logo_img" value="'.plugins_url( 'admin/images/icon_logo.png', dirname(__FILE__) ).'">
      <div id="theasys_search_results"></div>
    ';

    echo $html;

  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    wp_enqueue_style( 'wp-jquery-ui-dialog' );

    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/theasys-admin.css', array(), $this->version, 'all' );

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    wp_enqueue_script( 'jquery-ui-dialog' );

    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/theasys-admin.js', array( 'jquery' ), $this->version, false );

  }

  public function allow_my_custom_host( $allow, $host, $url ) {

    if ( $host === $this->options['host'] ) $allow = true;
    return $allow;

  }

  /**
   * Add menu and submenus in the admin area.
   *
   * @since    1.0.0
   */
  public function import_theasys_menu() {

    add_menu_page(
    __('Theasys'),
    __('Theasys'),
    'post',
    'theasys',
     '',
    plugins_url( 'admin/images/icon_logo.png', dirname(__FILE__) )
    );

    $submenu_pages = array(
      array(
        'parent_slug'   => 'theasys',
        'page_title'    => 'Information',
        'menu_title'    => 'Information',
        'capability'    => 'read',
        'menu_slug'     => 'theasys_information',
        'function'      => 'theasys_information'
      ),
      array(
        'parent_slug'   => 'theasys',
        'page_title'    => 'Settings',
        'menu_title'    => 'Settings',
        'capability'    => 'read',
        'menu_slug'     => 'theasys_settings',
        'function'      => 'theasys_settings'
      ),
    );

    foreach($submenu_pages as $submenu){

      add_submenu_page(
        $submenu['parent_slug'],
        $submenu['page_title'],
        $submenu['menu_title'],
        $submenu['capability'],
        $submenu['menu_slug'],
        array($this,$submenu['function'])
      );

    }

  }

  /**
   * Load virtual tours page
   *
   * @since    1.0.0
   */
  public function theasys_information() {

    include plugin_dir_path(__FILE__).'partials/theasys-admin-display-information.php';

  }

  /**
   * Load settings page
   *
   * @since    1.0.0
   */
  public function theasys_settings(){

    include plugin_dir_path(__FILE__).'partials/theasys-admin-display-settings.php';

  }

}
