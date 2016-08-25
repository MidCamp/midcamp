@api
Feature: Article Content Type
  As a Content Editor,
  I want to create an article,
  So I can provide timely updates for my users.

  Scenario: Validate the article content type has the expected fields.
    Given I am logged in as a user with the "administrator" role
    Then the form at "node/add/article" has the expected fields:
      | field                    | tag        | type             |
      | title                    | textfield  | text             |
      | field-article-summary    | textarea   |                  |
      | field-article-paragraphs | paragraphs | paragraphs-text  |
      | field-article-paragraphs | paragraphs | paragraphs-image |
