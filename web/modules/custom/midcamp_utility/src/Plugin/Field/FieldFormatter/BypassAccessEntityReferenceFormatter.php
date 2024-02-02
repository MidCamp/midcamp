<?php

declare(strict_types=1);

namespace Drupal\midcamp_utility\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\TranslatableInterface;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceEntityFormatter;

/**
 * Plugin implementation of the 'entity reference rendered entity' formatter.
 *
 * @FieldFormatter(
 *   id = "midcamp_utility_bypass_access_entity_reference",
 *   label = @Translation("Rendered entity (bypass access)"),
 *   description = @Translation("Display the referenced entities rendered by entity_view() without access checks."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
final class BypassAccessEntityReferenceFormatter extends EntityReferenceEntityFormatter {

  protected function getEntitiesToView(EntityReferenceFieldItemListInterface $items, $langcode) {
    $entities = [];

    foreach ($items as $delta => $item) {
      // Ignore items where no entity could be loaded in prepareView().
      if (!empty($item->_loaded)) {
        $entity = $item->entity;

        // Set the entity in the correct language for display.
        if ($entity instanceof TranslatableInterface) {
          $entity = \Drupal::service('entity.repository')->getTranslationFromContext($entity, $langcode);
        }

        $entity->_referringItem = $items[$delta];
        $entities[$delta] = $entity;
      }
    }

    return $entities;
  }

}
