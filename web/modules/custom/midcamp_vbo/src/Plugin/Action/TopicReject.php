<?php

namespace Drupal\midcamp_vbo\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Reject or un-confirm topic.
 *
 * @Action(
 *  id = "topic_reject",
 *  label = @Translation("Reject or un-confirm topic"),
 *  type = "node",
 * )
 */
class TopicReject extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    if ($entity->hasField('field_accepted_confirmed')) {
      $entity->field_accepted_confirmed->value = 0;
      $entity->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $access = $object->status->access('edit', $account, TRUE)
      ->andIf($object->field_accepted_confirmed->access('edit', $account, TRUE));

    return $return_as_object ? $access : $access->isAllowed();
  }

}
