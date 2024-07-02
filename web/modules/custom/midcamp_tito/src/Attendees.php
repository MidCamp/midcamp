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
    // If the event is not active, do not attempt to sync attendees.
    if ($this->activeEvent($event_status) == FALSE) {
      \Drupal::logger('midcamp_tito')->info('Event "%event" not active.', ['%event' => $tito_event_id]);
      return;
    }

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
      // Slow down to preventing bumping into the rate limit.
      sleep(1);
    }
    while ($page = end($results)['meta']['next_page']);

    if (empty($results)) {
      \Drupal::logger('midcamp_tito')->info('No attendees returned for event "%event"', ['%event' => $tito_event_id]);
      return;
    }

    foreach ($results as $result) {
      $attendees = array_merge($attendees, $result['tickets']);
    }

    if (!$attendees) {
      \Drupal::logger('midcamp_tito')->info('No attendees returned for event "%event"', ['%event' => $tito_event_id]);
      return;
    }

    // Gather user preferences on site display. This is ugly, but the API is
    // very limited in this area.
    $question = 'show-on-the-public-attendees-listing';
    $attendeePreferences = $this->getQuestion($tito_slug, $tito_event_id, $question);

    $this->batchProcessAttendees($attendees, $attendeePreferences, $tito_event_id);

  }

  /**
   * Process attendee creation/updates.
   *
   * @param array $attendees
   * @param array $attendeePreferences
   * @param string $tito_event_id
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function batchProcessAttendees($attendees, $attendeePreferences, $tito_event_id) {
    $sandbox = [];
    if (!isset($sandbox['progress'])) {

      // The count of nodes visited so far.
      $sandbox['progress'] = 0;

      // Total nodes that must be visited.
      $sandbox['max'] = count($attendees);

      // A place to store messages during the run.
      $sandbox['messages'] = [];

      // Last node read via the query.
      $sandbox['current_node'] = -1;
    }

    // @TODO - actually implement some batching.
    // Set a limit.
    $limit = 25;

    foreach ($attendees as $attendee) {
      $query = \Drupal::entityQuery('node');
      $queryResult = $query
        ->accessCheck(FALSE)
        ->condition('type', 'attendee')
        ->condition('field_attendee_id', $attendee['id'])
        ->execute();

      $entityNid = is_array($queryResult) ? current($queryResult) : null;
      $entity = is_numeric($entityNid) ? $this->entityTypeManager->getStorage('node')->load($entityNid) : null;

      if ($entity) {
        $entity->set('field_attendee_id', $attendee['id']);
        $entity->set('field_name', $attendee['name']);
        $entity->set('field_first_name', $attendee['first_name']);
        $entity->set('field_last_name', $attendee['last_name']);
        $entity->set('field_organization', isset($attendee['company_name']) ? $attendee['company_name'] : '');
        $entity->set('field_ticket_type', $attendee['release_title']);
        $entity->set('field_drupal_user_account', isset($attendee['email']) ? $this->getUserIdByEmail($attendee['email']) : '');
        $entity->set('field_ticket_state', $attendee['state']);
        $entity->set('field_display_on_site', isset($attendee['id']) ? $this->userDisplayPreference($attendee['id'], $attendeePreferences) : TRUE);
        $entity->set('status', isset($attendee['id']) ? $this->userDisplayPreference($attendee['id'], $attendeePreferences) : TRUE);
        $entity->save();
      }
      else {
        // Create the attendee if one does not yet exist.
        $entity = Node::create([
          'type' => 'attendee',
          'title' => !empty($attendee['name']) ? $attendee['name'] : $attendee['id'],
          'field_attendee_id' => $attendee['id'],
          'field_drupal_user_account' => isset($attendee['email']) ? $this->getUserIdByEmail($attendee['email']) : '',
          'field_name' => $attendee['name'],
          'field_first_name' => $attendee['first_name'],
          'field_last_name' => $attendee['last_name'],
          'field_email' => isset($attendee['email']) ? $attendee['email'] : '',
          'field_organization' => isset($attendee['company_name']) ? $attendee['company_name'] : '',
          'field_ticket_type' => $attendee['release_title'],
          'field_ticket_state' => $attendee['state'],
          'field_event' => $this->getEventById($tito_event_id),
          'field_display_on_site' => isset($attendee['id']) ? $this->userDisplayPreference($attendee['id'], $attendeePreferences) : TRUE,
          'status' => isset($attendee['id']) ? $this->userDisplayPreference($attendee['id'], $attendeePreferences) : TRUE,
        ]);
        $entity->save();
      }

      // Update our progress information.
      $sandbox['progress']++;
      $sandbox['current_node'] = $attendee['id'];
    }

    $sandbox['#finished'] = $sandbox['progress'] >= $sandbox['max'] ? TRUE : $sandbox['progress'] / $sandbox['max'];

    if ($sandbox['#finished']) {
      \Drupal::logger('midcamp_tito')->notice("Batch processing complete");
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

  /**
   * @param string $tito_slug
   * @param string $tito_event_id
   *
   * @param string $question
   *
   * @return array
   */
  protected function getQuestion($tito_slug, $tito_event_id, $question) {
    $results = [];

    do {
      // No query parameters initially.
      $query = '';
      // Handle pagination.
      if (isset($page)) {
        $query = $query . "&page=$page";
      }
      $results[] = $this->titoClient->request('get', "$tito_slug/$tito_event_id/questions/$question/answers", $query, []);
      // Slow down to preventing bumping into
      sleep(1);
    }
    while ($page = end($results)['meta']['next_page']);

    $answers = [];
    foreach ($results as $result) {
      $answers = array_merge($answers, $result['answers']);
    }

    return $answers;
  }

  /**
   * @param string $ticketId
   * @param array $answers
   *
   * @return mixed
   */
  protected function userDisplayPreference($ticketId, $answers) {
    // Attempt to find a response.
    $index = array_search($ticketId, array_column($answers, 'ticket_id'), TRUE);

    // If we have an answer and the answer is No, return false.
    if (isset($index) && $answers[$index]['response'] == 'No') {
      return FALSE;
    }

    // No answer or Yes answer returns TRUE.
    return TRUE;
  }

}
