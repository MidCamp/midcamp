<?php

/**
 * @file
 * Contains class FailureContext.
 */

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Driver\Selenium2Driver;
use Drupal\DrupalExtension\Context\MinkContext;

/**
 * Class FailureContext.
 */
class FailureContext implements Context {

  protected $contexts = array();
  protected $failurePath;

  /**
   * Prepare scenario to take screenshot on failed step.
   *
   * @param BeforeScenarioScope $scope
   *   Before Scenario hook scope.
   *
   * @BeforeScenario
   */
  public function prepare(BeforeScenarioScope $scope) {
    $this->contexts['mink'] = $scope->getEnvironment()
      ->getContext(MinkContext::class);
    $this->failurePath = $scope->getEnvironment()
      ->getSuite()
      ->getSetting('failure_path');
  }

  /**
   * Take screenshot on failure.
   *
   * @param AfterStepScope $scope
   *   After Step hook scope.
   *
   * @AfterStep
   */
  public function handleFailure(AfterStepScope $scope) {

    if (99 !== $scope->getTestResult()->getResultCode()) {
      return;
    }

    $fileName = $this->fileName($scope);
    $this->dumpMarkup($fileName);
    $this->screenShot($fileName);
  }

  /**
   * Take screenshot.
   *
   * @Then /^I take a screenshot$/
   */
  public function assertScreenShot() {
    $fileName = $this->fileName();
    $this->dumpMarkup($fileName);
    $this->screenShot($fileName);
  }

  /**
   * Compute a file name for the output.
   *
   * @param AfterStepScope $scope
   *   After Step hook scope.
   *
   * @return string
   *   Screenshot filename.
   */
  protected function fileName(AfterStepScope $scope = NULL) {
    if ($scope) {
      $baseName = pathinfo($scope->getFeature()->getFile());
      $baseName = substr($baseName['basename'], 0,
        strlen($baseName['basename']) - strlen($baseName['extension']) - 1);
      $baseName .= '-' . $scope->getStep()->getLine();
    }
    else {
      $baseName = 'screenshot';
    }

    $baseName .= '-' . date('YmdHis');
    $baseName = $this->failurePath . '/' . $baseName;
    return $baseName;
  }

  /**
   * Save the markup from the failed step.
   */
  protected function dumpMarkup($fileName) {
    $fileName .= '.html';
    $html = $this->contexts['mink']->getSession()->getPage()->getContent();
    file_put_contents($fileName, $html);
    sprintf("HTML available at: %s\n", $fileName);
  }

  /**
   * Save a screen shot from the failed step.
   */
  protected function screenShot($fileName) {
    $fileName .= '.png';
    $driver = $this->contexts['mink']->getSession()->getDriver();

    if ($driver instanceof Selenium2Driver) {
      file_put_contents($fileName,
        $this->contexts['mink']->getSession()->getDriver()->getScreenshot());
      sprintf("Screen shot available at: %s\n", $fileName);
      return;
    }
  }

}
