@api
Feature: Job Content Type
  As a Sponsor,
  I want to create Job,
  So I can provide users with employment opportunities.

  Scenario: Validate the job content type has the expected fields.
    Given I am logged in as a user with the "administrator" role
    Then the form at "node/add/job" has the expected fields:
      | field                 | tag       | type |
      | title                 | textfield | text |
      | field-job-type        | select    |      |
      | field-job-description | textarea  |      |
      | field-job-skill-level | select    |      |
