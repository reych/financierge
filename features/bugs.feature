Feature: Tests for Bug

	Scenario: When user tries to invoke budget information without specifying a category, amount spent and budget values should be assigned to zero
		Given user is logged in
			|username|cucumber|
	  		|password|cucumber|
		And user clears category field
		And user selects month of may 
		When user clicks “go”
		Then the budget and amount values should be set to zero

	Scenario: When user tries to invoke budget information without specifying a date, amount spent and budget values should be assigned to zero
		Given user is logged in
			|username|cucumber|
	  		|password|cucumber|
		And user enters “food” into category field
		And user clears month in input field
		When user clicks “go”
		Then the budget and amount values should be set to zero

	Scenario: When user tries to invoke graph interval without specifying a end date, graph doesn’t do anything
		Given user is logged in
			|username|cucumber|
	  		|password|cucumber|
		And user enters start date April 14th
		And user clears end date input field
		When user clicks “go”
		Then the graph does nothing

	Scenario: When user tries to reset budget, budget resets
		Given user is logged in
			|username|cucumber|
	  		|password|cucumber|
		And user enters “food” into category field
		And user selects month of may 
		And user has input the information by clicking “go”
		And user enters five dollars in budget input field
		And user clicks “change budget” button
		And user enters ten dollars in budget input field
		When user clicks “change budget” button
		Then the budget for food should be set to five dollars without using upload
