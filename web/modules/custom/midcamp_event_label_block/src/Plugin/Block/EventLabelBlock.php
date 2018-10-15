<?php

namespace Drupal\midcamp_event_label_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
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
        '#cache' => [
          'contexts' => $this->getCacheContexts(),
          'tags' => $this->getCacheTags(),
        ],
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

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    // Set cache context per route
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $currentNode = $this->getCurrentNode();

    if (($currentNode instanceof NodeInterface) && ($currentNode->hasField($this->eventField))) {
      $eventField = $currentNode->get($this->eventField);

      // Return cache tags for node and event term if it exists
      if (($eventField instanceof EntityReferenceFieldItemList) && !empty($eventField->getValue())) {
        $term = Term::load($eventField->target_id);
        $tags = [
          'term:' . $term->id(),
          'node:' . $currentNode->id(),
        ];
        return Cache::mergeTags(parent::getCacheTags(), $tags);
      }

      // Otherwise return cache tag for node
      return Cache::mergeTags(parent::getCacheTags(), ['node:' . $currentNode->id()]);
    }

    return parent::getCacheTags();
  }

}
