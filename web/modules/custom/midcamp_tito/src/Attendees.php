<?php

namespace Drupal\midcamp_tito;
use Drupal\tito\Client;

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
   * Constructs a new Attendees object.
   */
  public function __construct(Client $tito_client) {
    $this->titoClient = $tito_client;
  }

}
