<?php
/**
 * @file
 * The Behat feature context.
 */

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements SnippetAcceptingContext {

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
  }

  /**
   * @Then I cannot publish the node
   */
  public function cannotPublish()
  {
    $page = $this->getSession()->getPage();
    $element = $page->find('xpath', '//*[@id="edit-status-wrapper"]');
    // Seriously? WTF happened to all the $this->assertSomething() methods?
    if (!empty($element)) {
      throw new Exception("Found the Publishing Status field.");
    }
  }

}
