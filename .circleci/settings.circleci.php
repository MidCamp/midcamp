<?php

$databases['default']['default'] = array(
  'database' => 'drupal',
  'username' => 'drupal',
  'password' => 'drupal',
  'prefix' => '',
  'host' => '127.0.0.1',
  'port' => '',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

// This should already be read, but things are being weird.
$settings['install_profile'] = 'config_installer';
