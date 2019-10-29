@api
Feature: Topic Content Type
  As an Organizer,
  I want to add a topic to the website,
  So I can populate an event with all the things.

  Background:
    Given "topic_type" terms:
      | name          |
      | Schedule Item |
      | Session       |
      | Training      |
    And "event" terms:
      | name          |
      | MidCamp 2018  |
      | MidCamp 2019  |
      | MidCamp 2020  |

  Scenario: Validate the topic content type has the expected fields.
    Given I am logged in as a user with the "administrator" role
    Then the form at "node/add/topic" has the expected fields:
      | field             | tag       | type  |
      | title             | textfield | text  |
      | field-topic-type  | input     | radio |
      | field-people      | textfield | text  |
      | body              | textarea  |       |

  Scenario: Authenticated user role cannot publish Topic content
    Given I am logged in as a user with the "authenticated" role
    And I am at "node/add/topic"
    Then I should see "Draft" in the "#edit-moderation-state-0-state" element
    And I should see "Submitted" in the "#edit-moderation-state-0-state" element
    And I should not see "Accepted" in the "#edit-moderation-state-0-state" element