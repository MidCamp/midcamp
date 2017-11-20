@api
Feature: Jobs Page
  As an Anonymous User,
  I want to view job postings,
  So I can get hired by one of MidCamp's kick-ass sponsor companies.

  Scenario: Validate the jobs page has the expected fields.
    Given I am an anonymous user
    Then the form at "jobs" has the expected fields:
      | field                       | tag       | type |
      | field-job-type-value        | select    |      |
      | field-job-skill-level-value | select    |      |
