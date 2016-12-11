@api
Feature: Event Taxonomy
  As an Organizer,
  I want to add a event to the website,
  So I can publicize an event and subevents.

  Scenario: Validate the event taxonomy has the expected fields.
    Given I am logged in as a user with the "administrator" role
    Then the form at "admin/structure/taxonomy/manage/event/add" has the expected fields:
      | field             | tag       | type  |
      | name              | textfield | text  |
      | description       | textarea  |       |
      | field-date        | textfield | text  |
      | field-social-media| textfield | text  |
      | parent            | select    |       |