Feature: Add accounts

  Adding/Deleting accounts (bank, credit card, etc.) should be done through CSV upload.

  Scenario: The user deletes an account from the account list and it disappeares 
    Given user is logged in
      |username|cucumber|
      |password|cucumber|
    And user has an account to delete
    When user uploads the CSV with account delete information
    Then the account list should not have the account from the CSV

  Scenario: The user adds an account with the upload function and it appears in the account list
  	When user uploads the CSV with the account
  	Then the account list should have the account from the CSV
