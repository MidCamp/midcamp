@api
Feature: Sponsor Packages Taxonomy Vocabulary
  As an Organizer,
  I want to add a Sponsor Packages vocabulary term to the website,
  So I can track and manage sponsorship pacakges.

  Scenario: Validate the sponsor_packages taxonomy has the expected fields.
    Given I am logged in as a user with the "administrator" role
    Then the form at "admin/structure/taxonomy/manage/sponsor_packages/add" has the expected fields:
      | field                           | tag       | type   |
      | name                            | textfield | text   |
      | field-sponsor-package-price     | textfield | number |
      | field-sponsor-package-qty-avail | textfield | number |
      | description                     | textarea  |        |
