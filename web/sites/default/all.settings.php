<?php
/**
 * @file
 * Lagoon Drupal 8 all environment configuration file.
 *
 * This file should contain all settings.php configurations that are needed by all environments.
 *
 * It contains some defaults that the Lagoon team suggests, please edit them as required.
 */

// Defines where the sync folder of your configuration lives. In this case it's outside
// the web folder for an advanced security measure: '../config/sync'.
$settings['config_sync_directory'] = '../conf/drupal/config';
$settings['file_private_path'] = 'sites/default/files/private';

if (getenv('MAILCHIMP_API_KEY')) {
  $config['mailchimp.settings']['api_key'] = getenv('MAILCHIMP_API_KEY');
}

if (getenv('RECAPTCHA_SITE_KEY') && getenv('RECAPTCHA_SECRET_KEY')){
  $config['recaptcha.settings']['site_key'] = getenv('RECAPTCHA_SITE_KEY');
  $config['recaptcha.settings']['secret_key'] = getenv('RECAPTCHA_SECRET_KEY');
}

if (getenv('RECAPTCHA_V3_SITE_KEY') && getenv('RECAPTCHA_V3_SECRET_KEY')) {
  $config['recaptcha_v3.settings']['site_key'] = getenv('RECAPTCHA_V3_SITE_KEY');
  $config['recaptcha_v3.settings']['secret_key'] = getenv('RECAPTCHA_V3_SECRET_KEY');
}
