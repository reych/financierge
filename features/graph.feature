Feature: Graph

  	Scenario: When toggles account in account list corresponding line appears in the graph
		When user clicks on an account in the account list 
		And the account is not toggled
		Then the graph line for that account should show up. 

	Scenario: When a user adds a new line to the graph it is a different color from all the other lines
		When user clicks on an account in the account list 
		And the account is not toggled 
		Then the graph line for that account should show up. 
		And it should be a different color than all the other lines

	Scenario: When user views the page the graph line color will match the corresponding account. In this way, the user 
		Given there is an account in the account list
		And the account is not toggled 
		When user clicks on an account in the account list 
		Then the graph line for that account should show up and be the same color as the button color

	Scenario: When user views the dash there will be a liability line, networth line, and assets line on the graph
		Given user is logged in
		And the graph exists
		When user looks at dash
		Then they should see liability, assets, and net worth line on the graph

	Scenario: When user selects a time on the calendar, then the graph adjusts accordingly
		Given user is logged in
		When user selects a specific calendar date
		Then the graph should adjust to the correct interval

	Scenario: When a user clicks on a account button for a graphed account, the corresponding line will disappear
		Given user is logged in
		And an account has already been toggled
		When user clicks on account that has already been toggled 
		Then the line should not be in the graph

	Scenario: When user logs in the graph should show a three month line interval
		Given user is logged in
		When user views the dash
		Then it should be defaulted into a three month interval





