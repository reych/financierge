Feature: Load transactions

  Scenario: User clicks on one account
    Given nothing is yet displayed in the transaction box
    When the user clicks on <account>
    Then all the transactions for <account> are displayed in the transactions widget.

  Scenario: User clicks a second account
    Given there is already an account displayed in the transactions widget
    When User clicks <account2>
    Then...
