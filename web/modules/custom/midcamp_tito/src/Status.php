<?php

namespace Drupal\midcamp_tito;

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
    // @todo Add config for setting orgnization.
    $event = $this->titoClient->request('get', "exampleorganization/$tito_event_id/", '');

    $event = reset($event);

    if ($event['archived'] == TRUE) {
      return 'archived';
    }
    elseif ($event['live'] == TRUE) {
      return 'live';
    }
  }

}
