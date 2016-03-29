Feature: Sort transactions

  User can sort by date, category, and amount

  Scenario: Sort by date
    Given account transactions are displayed
    When the user clicks the date button
    Then the transactions should be sorted from earliest date to latest.

  Scenario: Sort by category
    Given account transactions are displayed
    When the user clicks the category button
    Then the transactions should be sorted in alphabetical order according to category (tie breakers?)

  Scenario: Sort by amount
    Given account transactions are displayed
    When the user clicks the amount button
    Then the transactions should be sorted from least amount to greatest.
