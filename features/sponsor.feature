@api
Feature: Sponsor Content Type
  As an Organizer,
  I want to add a sponsor to the website,
  So I can give credit to the people and organizations who make it happen.

  Scenario: Validate the sponsor content type has the expected fields.
    Given I am logged in as a user with the "administrator" role
    Then the form at "node/add/sponsor" has the expected fields:
      | field       | tag       | type |
      | title       | textfield | text |
      | field-link  | textfield | text |
      | body        | textarea  |      |
