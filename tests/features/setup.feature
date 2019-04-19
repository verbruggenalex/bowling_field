@api @javascript
Feature: Setup
  A user needs to be able to record bowling scores.

  Scenario: Create nodes with fields
    Given "bowling" content:
    | title                     | promote |
    | Bowling type with records |       1 |
    When I am on the homepage
    And follow "Bowling type with records"
    Then I should see the text "Bowling type with records"