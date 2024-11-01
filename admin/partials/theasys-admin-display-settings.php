<?php

/**
 * @link       theasys.io
 * @since      1.0.0
 *
 * @package    Theasys
 * @subpackage Theasys/admin/partials
 */

 $updated = false;

  if(isset($_POST)){

    $this->options = $_POST + $this->options;

    $updated = update_option( 'theasys', $this->options );

  }

  $this->options = get_option('theasys');

?>
  <div class="wrap">
    <h1><?php _e('Theasys Settings','theasys'); ?></h1>
<?php if( $updated ){ ?>
    <div class="updated notice is-dismissible">
      <p><?php _e('API Key updated, awesome!','theasys'); ?></p>
    </div>
<?php } ?>
<?php if( !isset( $this->options['api_key'] ) || $this->options['api_key'] === '' ){ ?>
    <div class="notice notice-error">
      <p><?php _e('API Key is missing.'); ?></p>
    </div>
<?php } ?>
    <div class="card">
      <img width="250" src="<?php echo plugins_url( 'images/logo.png', dirname(__FILE__) ); ?>" class="theasys-img-responsive theasys-img-responsive-center" alt="theasys logo">
      <h2><?php _e('Get your API Key:'); ?></h2>
      <p><a target="_blank" href="<?php echo $this->options['website_url']; ?>signin/"><?php _e('Sign in','theasys'); ?></a> <?php _e('to your Theasys.io account and generate one from the','theasys'); ?> <a target="_blank" href="<?php echo $this->options['website_url']; ?>dashboard/api-keys/"><?php _e('API Keys','theasys'); ?></a> <?php _e('page'); ?>.</p>
      <div class="theasys-spacer"></div>
      <h2><?php _e('Or enter an API Key:'); ?></h2>
      <p><?php _e('Already have your key? Enter it here.','theasys'); ?></p>
      <div class="form-wrap">
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
          <div class="form-field form-required term-name-wrap">
            <textarea id="theasys_api_key" name="api_key" rows="6" class="large-text code"><?php echo isset($this->options['api_key']) ? $this->options['api_key'] : ''; ?></textarea>
          </div>
          <?php submit_button(_('Save Changes'),'primary','submit',false); ?>
<?php if( isset($this->options['api_key']) && $this->options['api_key'] !=='' ){ ?>
          <button id="theasys_test_api_key" type="button" class="button"><?php _e('Test API Key','theasys'); ?></button>
          <span id="theasys_test_api_key_loading" class="hidden">
            <img src="<?php echo plugins_url( 'images/loading.gif', dirname(__FILE__) ); ?>" alt="loading">
          </span>
          <div id="theasys_test_api_key_response"></div>
<?php } ?>
        </form>
      </div>
    </div>
    <input type="hidden" id="theasys_api_url" value="<?php echo $this->options['api_url']; ?>">
    <input type="hidden" id="theasys_website_url" value="<?php echo $this->options['website_url']; ?>">
  </div>
<?php

