<?php
/**
 * @file
 * A Behat context for Drupal fields.
 *
 * @copyright Copyright (c) 2016 Palantir.net
 */

use Behat\Gherkin\Node\TableNode;
use Palantirnet\PalantirBehatExtension\Context\SharedDrupalContext;

/**
 * A Behat context for Drupal fields.
 */
class DrupalFieldContext extends SharedDrupalContext {

  /**
   * Asserts a page has fields provided in the form of a given type.
   *
   * Example of type checks:
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
   * @param string $path
   *   The URL path.
   * @param string $content_type
   *   The machine name of the content type.
   * @param Behat\Gherkin\Node\TableNode $fieldsTable
   *   A Behat table.
   *
   * @throws \Exception
   *
   * @Then the form at :path has the expected fields:
   * @Then the content type :type has the expected fields:
   */
  public function assertFields($path = '', $content_type = '', TableNode $fieldsTable = NULL) {
    // Load the page with the form on it.
    if (empty($path)) {
      $path = 'node/add/' . $content_type;
    }
    $this->getSession()->visit($this->locatePath($path));
    $page = $this->getSession()->getPage();

    if (NULL == $fieldsTable) {
      throw new \Exception('You must use the field assertion with a list of fields.');
    }

    foreach ($fieldsTable->getHash() as $row) {
      $fieldSelector = '#edit-' . $row['field'] . '-0-value';
      $page->hasField($fieldSelector);
      $this->assertFieldType('#edit-' . $row['field'], $row['tag'], $row['type']);
    }
  }

  /**
   * Test a field on the current page to see if it matches the field type.
   *
   * @param string $field
   *   The field's input name.
   * @param string $expectedTag
   *   The field's tag name.
   * @param string $expectedType
   *   The type of field.
   *
   * @throws \Exception
   *
   * @Then the ":field" field is ":tag"
   * @Then the ":field" field is ":tag" with type ":type"
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
   * @param string $field
   *   The field's input name.
   * @param string $expectedType
   *   The type of field.
   *
   * @throws \Exception
   */
  public function assertTextarea($field, $expectedType) {
    $element = $this->getSession()->getPage()->find('css', $field . '-wrapper');
    if (NULL == $element || NULL == $element->find('css', 'textarea.form-textarea')) {
      throw new Exception(sprintf("Couldn't find %s of type textarea.", $field));
    }
  }

  /**
   * Verify the field is an input field of the given type.
   *
   * @param string $field
   *   The field's input name.
   * @param string $expectedType
   *   The type of field.
   *
   * @throws \Exception
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
   * @param string $field
   *   The field's input name.
   * @param string $expectedType
   *   The type of field.
   *
   * @throws \Exception
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
   * @param string $field
   *   The field's input name.
   * @param string $expectedType
   *   The type of field.
   *
   * @throws \Exception
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
   * @param string $field
   *   The field's input name.
   * @param string $expectedType
   *   The type of field.
   *
   * @throws \Exception
   */
  public function assertSelect($field, $expectedType) {
    $element = $this->getSession()->getPage()->find('css', $field);
    if (NULL == $element || NULL == $element->find('css', 'select.form-select')) {
      throw new Exception(sprintf("Couldn't find %s of type select.", $field));
    }
  }

  /**
   * Verify the field is a paragraph field.
   *
   * @param string $field
   *   The field's input name.
   * @param string $expectedType
   *   The type of field.
   *
   * @throws \Exception
   */
  public function assertParagraphs($field, $expectedType = '') {
    $element = $this->getSession()->getPage()->find('css', $field . '-wrapper');
    if (NULL == $element || NULL == $element->find('css', $field . '-add-more-add-more-button-' . $expectedType)) {
      throw new Exception(sprintf("Couldn't find %s of paragraph type %s", $field, $field . '-add-more-add-more-button-' . $expectedType));
    }
  }

  /**
   * Verify the field is an address field.
   *
   * @param string $field
   *   The field's input name.
   * @param string $expectedType
   *   The type of field.
   *
   * @throws \Exception
   */
  public function assertAddress($field, $expectedType = '') {
    $element = $this->getSession()->getPage()->find('css', $field . '-wrapper');
    if (NULL == $element) {
      throw new Exception(sprintf("Couldn't find %s address field", $field . '-wrapper'));
    }
  }

}
