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
   */
  public function __construct(Client $tito_client) {
    $this->titoClient = $tito_client;
  }

  /**
   * Check the status of the event.
   *
   * @param int $tito_event_id
   * @return string
   */
  public function check($tito_event_id) {
    // Get the status response for the given event ID.
    $event = $this->titoClient->request('get', "midcamp/$tito_event_id/", '');

    $event = reset($event);

    if ($event['archived'] == TRUE) {
      return 'archived';
    }
    elseif ($event['live'] == TRUE) {
      return 'live';
    }
  }

  /**
   * Update an Event Term.
   *
   * @param \Drupal\taxonomy\Entity\Term $event
   */
  public function update(Term $event) {
    // Call the API to check the event status
//    $tito_event_id = $event->get('field_event_id')->value;
    $tito_slug = $event->get('field_tito_slug')->value;
    $status = $this->check($tito_slug);

    // Set the status and save the node
    $event->set('field_event_status', $status);
    $event->save();
  }

}
