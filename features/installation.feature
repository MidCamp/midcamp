@api
Feature: Installation
  As a Drupal developer
  I want Drupal to be installed
  So that I can rely on the build for my project.

  Scenario: Verify The site has a login page with user and pass fields.
    Given I am not logged in
    Then the form at "user/login" has the expected fields:
      | field | tag   | type     |
      | name  | input | text     |
      | pass  | input | password |
