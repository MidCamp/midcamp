<?php
namespace Drupal\midcamp_vbo\Plugin\Action;
use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;
/**
 * Accept and confirm topic.
 *
 * @Action(
 *   id = "topic_accept_confirm",
 *   label = @Translation("Accept and confirm topic"),
 *   type = "node"
 * )
 */
class TopicAcceptConfirm extends ActionBase {
  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    if ($entity->hasField('field_accepted_confirmed')) {
      $entity->field_accepted_confirmed->value = 1;
      $entity->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $result = $object->access('update', $account, TRUE)
      ->andIf($object->field_accepted_confirmed->access('edit', $account, TRUE));
    return $return_as_object ? $result : $result->isAllowed();
  }
}