<?php

/**
 * @file
 *
 * @copyright Copyright (c) 2016 Palantir.net
 */

use Behat\Gherkin\Node\TableNode;
use Palantirnet\PalantirBehatExtension\Context\SharedDrupalContext;

class DrupalFieldContext extends SharedDrupalContext {


  /**
   * Asserts a page has fields provided in the form of a given type:
   * | field               | tag      | type  |
   * | title               | input    | text  |
   * | body                | textarea |       |
   * | field-subheadline   | input    | text  |
   * | field-author        | input    | text  |
   * | field-summary       | textarea |       |
   * | field-full-text     | textarea |       |
   * | field-ref-sections  | select   |       |
   *
   * Assumes fields are targeted with #edit-<fieldname>. For example,
   * "body" checks for the existence of the element, "#edit-body". Note, for
   * almost everything this will begin with "field-", like "field-tags".
   *
   * @Then the form at :path has the expected fields:
   * @Then the content type :type has the expected fields:
   *
   * @param String $path
   * @param TableNode $fieldsTable
   * @param String $content_type
   * @throws \Exception
   */
  public function assertFields($path = '', $content_type = '', TableNode $fieldsTable) {
    // Load the page with the form on it.
    if (empty($path)) {
      $path = 'node/add/' . $content_type;
    }
    $this->getSession()->visit($this->locatePath($path));
    $page = $this->getSession()->getPage();

    foreach ($fieldsTable->getHash() as $row) {
      $fieldSelector = '#edit-' . $row['field'] . '-0-value';
      $page->hasField($fieldSelector);
      $this->assertFieldType('#edit-' . $row['field'], $row['tag'], $row['type']);
    }
  }

  /**
   * Test a field on the current page to see if it matches
   * the expected HTML field type.
   *
   * @Then the ":field" field is ":tag"
   * @Then the ":field" field is ":tag" with type ":type"
   *
   * @param string $field
   * @param string $expectedTag
   * @param string $expectedType
   * @throws Exception
   */
  public function assertFieldType($field, $expectedTag, $expectedType = '') {
    $callback = 'assert' . ucfirst($expectedTag);
    if (!method_exists($this, $callback)) {
      throw new Exception(sprintf('%s is not a field tag we know how to validate.',
        $expectedTag));
    }
    $this->$callback($field, $expectedType);
  }

  /**
   * Verify the field is a textarea.
   *
   * @param $field
   * @param $expectedType
   * @throws Exception
   */
  public function assertTextarea($field, $expectedType) {
    $element = $this->getSession()->getPage()->find('css', $field . '-wrapper');
    if (NULL == $element->find('css', 'textarea.form-textarea')) {
      throw new Exception(sprintf("Couldn't find %s of type textarea.", $field));
    }
  }

  /**
   * Verify the field is an input field of the given type.
   *
   * @param $field
   * @param $expectedType
   * @throws Exception
   */
  public function assertInput($field, $expectedType) {
    $element = $this->getSession()->getPage()->find('css', $field);
    if (NULL == $element || NULL == $element->find('css', 'input[type="' . $expectedType . '"]')) {
      throw new Exception(sprintf("Couldn't find %s of type %s", $field, $expectedType));
    }
  }

  /**
   * Verify the field is an input field of the given type.
   *
   * @param $field
   * @param $expectedType
   * @throws Exception
   */
  public function assertTextfield($field, $expectedType) {
    $element = $this->getSession()->getPage()->find('css', $field . '-wrapper');
    if (NULL == $element || NULL == $element->find('css', 'input[type="' . $expectedType . '"]')) {
      throw new Exception(sprintf("Couldn't find %s of type %s", $field, $expectedType));
    }
  }

  /**
   * Verify the field is an input field of the given type.
   *
   * @param $field
   * @param $expectedType
   * @throws Exception
   */
  public function assertFile($field, $expectedType) {
    $element = $this->getSession()->getPage()->find('css', $field . '-wrapper');
    if (NULL == $element || NULL == $element->find('css', 'input[type="file"]')) {
      throw new Exception(sprintf("Couldn't find %s of type %s", $field, $expectedType));
    }
  }

  /**
   * Verify the field is a select list.
   *
   * @param $field
   * @param $expectedType
   * @throws Exception
   */
  public function assertSelect($field, $expectedType) {
    $element = $this->getSession()->getPage()->find('css', $field);
    if (NULL == $element->find('css', 'select.form-select')) {
      throw new Exception(sprintf("Couldn't find %s of type select.", $field));
    }
    // Verify that the select list is not part of a multivalue widget.
    if (!$element->find('css', 'select.form-select')->isVisible()) {
      throw new Exception(sprintf("Couldn't find %s of type select.", $field));
    }
  }

  /**
   * Verify the field is a paragraph field.
   *
   * @param $field
   * @param $expectedType
   * @throws Exception
   */
  public function assertParagraphs($field, $expectedType = '') {
    $element = $this->getSession()->getPage()->find('css', $field . '-wrapper');
    if (NULL == $element || NULL == $element->find('css', $field . '-add-more-add-more-button-' . $expectedType)) {
      throw new Exception(sprintf("Couldn't find %s of paragraph type %s", $field, $field . '-add-more-add-more-button-' . $expectedType));
    }
  }
}/