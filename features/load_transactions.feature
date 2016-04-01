Feature: Load transactions

  Scenario: User clicks on one account
    Given nothing is yet displayed in the transaction box
      |username|zhongyag@usc.edu|
      |password|zg|
    When the user clicks on Checking
    Then all the transactions for Checking are displayed in the transactions widget.

  Scenario: User clicks a second account
    Given there is already an account displayed in the transactions widget
      |username|zhongyag@usc.edu|
      |password|zg|
    When User clicks Savings
    Then Savings transaction info is displayed
