Feature: Add accounts

  Adding accounts (bank, credit card, etc.) should be done through CSV upload.

  Scenario: The user deletes an account
  	Given user is logged in
  		|username|zhongyag@usc.edu|
  		|password|zg|
  	And user has a CSV that reflects a missing account
  	When user uploads the CSV
  	Then the account list should not have the account missing on the CSV

  Scenario: The user adds an account
  	Given user is logged in
  		|username|zhongyag@usc.edu|
  		|password|zg|
  	When user uploads the CSV
  	Then the account list should have the account from the CSV
