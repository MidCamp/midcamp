<?php

namespace Drupal\midcamp_event_label_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\NodeInterface;
use Drupal\core\Field\EntityReferenceFieldItemList;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'EventLabelBlock' block.
 *
 * @Block(
 *  id = "event_label_block",
 *  admin_label = @Translation("Event label block"),
 * )
 */
class EventLabelBlock extends BlockBase {

  protected $eventField = 'field_event';

  protected $currentNode;

  protected $currentEvent;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $currentNode = $this->getCurrentNode();

    $build = [];

    /**
     * Build the block on nodes with the eventField present.
     */
    if (($currentNode instanceof NodeInterface) && ($currentNode->hasField($this->eventField))) {
      $currentEvent = $this->getCurrentEvent();

      $build = [
        '#theme' => 'event_label_block',
        '#content' => $currentEvent,
      ];
    }

    return $build;
  }

  /**
   * Returns the node from the current route.
   * @return mixed|null
   */
  protected function getCurrentNode() {
    if (!isset($this->currentNode)) {
      $this->currentNode = \Drupal::routeMatch()->getParameter('node');
    }
    return $this->currentNode;
  }

  /**
   * Loads the event field from a node, returns a link to the term.
   * @return \Drupal\Core\Link
   */
  protected function getCurrentEvent() {
    if (!isset($this->currentEvent)) {
      $currentNode = $this->getCurrentNode();

      if (($currentNode instanceof NodeInterface) && ($currentNode->hasField($this->eventField))) {
        $eventField = $currentNode->get($this->eventField);

        if (($eventField instanceof EntityReferenceFieldItemList) && !empty($eventField->getValue())) {
          $term = Term::load($eventField->target_id);
          $this->currentEvent = $term->toLink();
        }
      }
    }

    return $this->currentEvent;
  }

}
