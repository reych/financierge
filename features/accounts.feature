Feature: Add accounts

  Adding accounts (bank, credit card, etc.) should be done through CSV upload.

  Scenario: The user deletes an account
    Given user is logged in
      |username|christdv@usc.edu|
      |password|christdv|
    And user has an account to delete
    When user uploads the CSV with account delete information
    Then the account list should not have the account from the CSV

  Scenario: The user adds an account
  	When user uploads the CSV with the account
  	Then the account list should have the account from the CSV
