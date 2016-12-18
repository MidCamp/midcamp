@api
Feature: Topic Content Type
  As an Organizer,
  I want to add a topic to the website,
  So I can populate an event with all the things.

  Scenario: Validate the topic content type has the expected fields.
    Given I am logged in as a user with the "administrator" role
    Then the form at "node/add/topic" has the expected fields:
      | field             | tag       | type  |
      | title             | textfield | text  |
      | field-topic-type  | input     | radio |
      | field-event       | select    |       |
      | field-people      | textfield | text  |
      | body              | textarea  |       |
