<?php

namespace Drupal\midcamp_tito;

use Drupal\node\Entity\Node;
use Drupal\tito\Client;
use Drupal\taxonomy\Entity\Term;

/**
 * Class Status.
 */
class Status {

  /**
   * Drupal\tito\Client definition.
   *
   * @var \Drupal\tito\Client
   */
  protected $titoClient;

  /**
   * Constructs a new Status object.
   *
   * @param \Drupal\tito\Client $tito_client
   */
  public function __construct(Client $tito_client) {
    $this->titoClient = $tito_client;
  }

  /**
   * Check the status of the event.
   *
   * @param string $tito_slug
   * @param string $tito_event_id
   * @return string
   */
  public function check($tito_slug, $tito_event_id) {
    // Get the status response for the given event ID.
    $event = $this->titoClient->request('get', "$tito_slug/$tito_event_id/", '');

    if ($event) {
      $event = reset($event);
    }

    if ($event['archived'] == TRUE) {
      return 'archived';
    }
    elseif ($event['live'] == TRUE) {
      return 'live';
    }

    return 'unknown';
  }

  /**
   * Update an Event Term.
   *
   * @param \Drupal\taxonomy\Entity\Term $event
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function update(Term $event) {
    // Call the API to check the event status
    $tito_slug = $event->get('field_tito_slug')->value;
    $tito_event_id = $event->get('field_event_id')->value;
    $status = $this->check($tito_slug, $tito_event_id);

    // Set the status and save the node
    $event->set('field_event_status', $status);
    $event->save();
  }

}
