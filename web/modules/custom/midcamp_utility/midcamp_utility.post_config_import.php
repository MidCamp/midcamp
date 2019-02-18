<?php

/**
 * @file
 * Contains post config import hooks for midcamp_utility module.
 */

use Drupal\Core\Config\ConfigImporter;

/**
 * Implements hook_post_config_import_NAME().
 * Adds event terms to existing users with the `organizer` role.
 */
function midcamp_utility_post_config_import_organizer_update(&$sandbox, ConfigImporter $configImporter) {
  $organizer_uids = \Drupal::entityQuery('user')
    ->condition('status', 1)
    ->condition('roles', 'organizer')
    ->execute();

  $organizers = \Drupal\user\Entity\User::loadMultiple($organizer_uids);

  foreach ($organizers as $organizer) {
    $organizer->field_organizer_year[] = ['target_id' => 2];
    $organizer->field_organizer_year[] = ['target_id' => 97];
    $organizer->save();
  }
}