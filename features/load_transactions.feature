Feature: Load transactions

  Scenario: User clicks on one account and transactions are displayed
    Given nothing is yet displayed in the transaction box
      |username|christdv@usc.edu|
      |password|christdv|
    When the user clicks on Checking
    Then all the transactions for Checking are displayed in the transactions widget.

  Scenario: User clicks a two accounts and transactions for both are displayed
    When User clicks Savings
    Then Savings transaction info is displayed
