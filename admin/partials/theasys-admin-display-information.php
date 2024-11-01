<?php

/**
 * @link       theasys.io
 * @since      1.0.0
 *
 * @package    Theasys
 * @subpackage Theasys/admin/partials
 */

  $theasys_options = get_option('theasys');
?>
  <div class="wrap">
    <h1><?php _e('Theasys Plugin','theasys'); ?></h1>
    <div class="card">
      <img width="250" src="<?php echo plugins_url( 'images/logo.png', dirname(__FILE__) ); ?>" class="theasys-img-responsive theasys-img-responsive-center" alt="theasys logo">
      <img src="<?php echo plugins_url( 'images/cover.png', dirname(__FILE__) ); ?>" class="theasys-img-responsive" alt="theasys cover image">
      <p><?php printf( __('Thank you for installing the official wordpress plugin for <a target="_blank" rel="noopener" href="%s">Theasys</a>.','theasys'),$theasys_options['website_url']); ?></p>
      <p><?php _e('This plugin allows Theasys registered users to easily embed their Virtual Tours into any post or page on their wordpress website.','theasys'); ?></p>
      <p><?php printf( __('Already a Theasys registered user? Start using this plugin by inserting your API Key in the <a href="%s">settings</a> page.','theasys'),admin_url('admin.php?page=theasys_settings')); ?></p>
      <div class="clear"></div>
<?php if( !isset( $theasys_options['api_key'] ) || $theasys_options['api_key'] === '' ){ ?>
        <div class="notice notice-warning">
          <p><?php _e('Please enter API Key into theasys settings'); ?> <a href="<?php echo admin_url('admin.php?page=theasys_settings'); ?>"><?php _e('page','theasys'); ?></a>.</p>
        </div>
<?php } ?>
    </div>
  </div>
<?php
