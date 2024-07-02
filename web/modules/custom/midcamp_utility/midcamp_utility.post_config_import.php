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
function midcamp_utility_post_config_import_organizer_update_2018(&$sandbox, ConfigImporter $configImporter) {
  // Last year's organizers were set by role.
  $organizer_uids = \Drupal::entityQuery('user')
    ->accessCheck(TRUE)
    ->condition('status', 1)
    ->condition('roles', 'organizer')
    ->execute();

  $organizers = \Drupal\user\Entity\User::loadMultiple($organizer_uids);

  foreach ($organizers as $organizer) {
    $organizer->field_organizer_year[] = ['target_id' => 2];
    $organizer->save();
  }
}

/**
 * Implements hook_post_config_import_NAME().
 * Adds 2019 event term to 2019 event organizers.
 */
function midcamp_utility_post_config_import_organizer_update_2019(&$sandbox, ConfigImporter $configImporter) {
  // uids of 2019 event organizers.
  $organizer_uids = [157, 3, 170, 13, 6, 25, 26, 67, 14, 16, 9, 7, 48, 4, 161];

  $organizers = \Drupal\user\Entity\User::loadMultiple($organizer_uids);

  foreach ($organizers as $organizer) {
    $organizer->field_organizer_year[] = ['target_id' => 97];
    $organizer->save();
  }
}
