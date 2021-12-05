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
$settings['install_profile'] = 'config_installer';
$settings['file_private_path'] = 'sites/default/files/private';

if (getenv('MAILCHIMP_API_KEY')) {
  $config['mailchimp.settings']['api_key'] = getenv('MAILCHIMP_API_KEY');
}
