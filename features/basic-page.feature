@api
Feature: Basic Page Content Type
  As a Content Editor,
  I want to create a basic page,
  So I can provide static content for my users.

  Scenario: Validate the basic page content type has the expected fields.
    Given I am logged in as a user with the "administrator" role
    Then the form at "node/add/page" has the expected fields:
      | field                 | tag        | type             |
      | title                 | textfield  | text             |
      | field-page-paragraphs | paragraphs | paragraphs-text  |
      | field-page-paragraphs | paragraphs | paragraphs-image |
