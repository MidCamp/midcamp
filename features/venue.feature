@api
Feature: Venue Content Type
  As a Content Editor,
  I want to create a venue,
  So I can organize a camp at a location.

  Scenario: Validate the venue content type has the expected fields.
    Given I am logged in as a user with the "administrator" role
    Then the form at "node/add/venue" has the expected fields:
      | field                            | tag        | type             |
      | title                            | textfield  | text             |
      | field-venue-events               | textfield  | text             |
      | field-address                    | address    |                  |
      | field-venue-paragraphs           | paragraphs | paragraphs-text  |
      | field-venue-paragraphs           | paragraphs | paragraphs-image |
