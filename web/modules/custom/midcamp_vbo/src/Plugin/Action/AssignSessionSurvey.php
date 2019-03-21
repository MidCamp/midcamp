<?php

namespace Drupal\midcamp_vbo\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'AssignSessionSurvey' action.
 *
 * @Action(
 *  id = "assign_session_survey",
 *  label = @Translation("Assign session survey"),
 *  type = "node",
 * )
 */
class AssignSessionSurvey extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($object = NULL) {
    // Add the `session_feedback` survey to the node.
    if ($object->hasField('field_feedback_form')) {
      // If there is a scheduled end time, schedule the webform to open then.
      if ($object->hasField('field_schedule_time') && $object->field_schedule_time->end_value) {
        $object->field_feedback_form->target_id = 'session_feedback';
        $object->field_feedback_form->status = 'scheduled';
        $object->field_feedback_form->open = $object->field_schedule_time->end_value;
        $object->save();
      }
      // Otherwise, just open the webform.
      else {
        $object->field_feedback_form->target_id = 'session_feedback';
        $object->field_feedback_form->status = 'open';
        $object->save();
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $access = $object->status->access('edit', $account, TRUE)
      ->andIf($object->access('update', $account, TRUE));

    return $return_as_object ? $access : $access->isAllowed();
  }

}
