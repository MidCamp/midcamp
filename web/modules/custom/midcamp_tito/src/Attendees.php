<?php

namespace Drupal\midcamp_tito;
use Drupal\tito\Client;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;

/**
 * Class Attendees.
 */
class Attendees {

  /**
   * Drupal\tito\Client definition.
   *
   * @var \Drupal\tito\Client
   */
  protected $titoClient;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new Attendees object.
   *
   * @param \Drupal\tito\Client $tito_client
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(Client $tito_client, EntityTypeManagerInterface $entity_type_manager) {
    $this->titoClient = $tito_client;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Create attendee entities for the given event.
   *
   * @param string $tito_slug
   * @param string $tito_event_id
   * @param string $event_status
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function sync($tito_slug, $tito_event_id, $event_status) {
    // If the event is not active, to not attempt to sync attendees.
    if ($this->activeEvent($event_status) == FALSE) {
      return;
    }

    $entityStorage = $this->entityTypeManager->getStorage('node');

    // Define an array to capture attendees.
    $attendees = [];

    do {
      // No query parameters initially, only fetch completed tickets.
      $query = '?search[states][]=complete';
      // Handle pagination.
      if (isset($page)) {
        $query = $query . "&page=$page";
      }
      $results[] = $this->titoClient->request('get', "$tito_slug/$tito_event_id/tickets/", $query, []);
    }
    while ($page = end($results)['meta']['next_page']);

    foreach ($results as $result) {
      $attendees = array_merge($attendees, $result['tickets']);
    }

    // Iterate over each page of response results to load user data.
    foreach ($attendees as $attendee) {
        // Check for existing attendee node so we can update existing.
        $query = \Drupal::entityQuery('node')
          ->condition('type', 'attendee')
          ->condition('field_attendee_id', $attendee['id']);
        $entity_nid = $query->execute();
        $entity = $entityStorage->load(current($entity_nid));

        if ($entity) {
          $entity->set('field_attendee_id', $attendee['id']);
          $entity->set('field_name', $attendee['name']);
          $entity->set('field_first_name', $attendee['first_name']);
          $entity->set('field_last_name', $attendee['last_name']);
          $entity->set('field_organization', isset($attendee['company_name']) ? $attendee['company_name'] : '');
          $entity->set('field_ticket_type', $attendee['release_title']);
          $entity->set('assoc_drupal_user', isset($attendee['email']) ? $this->getUserIdByEmail($attendee['email']) : '');
          $entity->set('field_ticket_state', $attendee['state']);
          $entity->save();
        }
        else {
          // Create the attendee if one does not yet exist.
          $entity = Node::create([
            'type' => 'attendee',
            'title' => !empty($attendee['name']) ? $attendee['name'] : $attendee['id'],
            'field_attendee_id' => $attendee['id'],
            'assoc_drupal_user' => isset($attendee['email']) ? $this->getUserIdByEmail($attendee['email']) : '',
            'field_name' => $attendee['name'],
            'field_first_name' => $attendee['first_name'],
            'field_last_name' => $attendee['last_name'],
            'field_email' => isset($attendee['email']) ? $attendee['email'] : '',
            'field_organization' => isset($attendee['company_name']) ? $attendee['company_name'] : '',
            'field_ticket_type' => $attendee['release_title'],
            'field_ticket_state' => $attendee['state'],
            'field_event' => $this->getEventById($tito_event_id),
          ]);
          $entity->save();
        }
    }
  }

  /**
   * Determine whether the event is still active.
   *
   * @param string $event_status
   * @return bool
   */
  protected function activeEvent($event_status) {
    $inactive_states = ['archived'];
    if (in_array($event_status, $inactive_states)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Look up Drupal user ID by email.
   * @param $email
   * @return bool
   */
  protected function getUserIdByEmail($email) {
    $uid = FALSE;

    $user = user_load_by_mail($email);

    if ($user) {
      $uid = $user->id();
    }

    return $uid;
  }

  /**
   * Look up Event by Tito ID value.
   * @param $id
   * @return bool|int|string|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getEventById($id) {
    $entity_id = FALSE;

    $entities = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties([
        'vid' => 'event',
        'field_event_id' => $id,
      ]);

    if ($entities) {
      // For now, assume one event Term.
      $entity = reset($entities);
      $entity_id = $entity->id();
    }
    return $entity_id;
  }

}
