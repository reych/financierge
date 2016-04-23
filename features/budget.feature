Feature: Budget
	Scenario: When user logs in they should see that they can set a budget for a category
		Given user is logged in
			|username|cucumber|
			|password|cucumber|
		And user enters “food” into category field
		And user selects month of may 
		And user has input the information by clicking “go”
		And user enters five dollars in budget input field
		When user clicks “change budget” buttonThen the budget for food should be set to five dollars

	Scenario: Either amount spent and/or budget value is shown in red if over budget 
		Given user is logged in
			|username|cucumber|
			|password|cucumber|
		And user enters “food” into category field
		And ten dollars have been spent on food
		And user selects month of may 
		And user has input the information by clicking “go”
		And user enters five dollars in budget input field
		When user clicks “change budget” button
		Then the amount spent value should be displayed in red on the dashboard

	Scenario: Either amount spent and/or budget value is shown in yellow if on budget 
		Given user is logged in
			|username|cucumber|
			|password|cucumber|
		And user enters “food” into category field
		And ten dollars have been spent on food
		And user selects month of may 
		And user has input the information by clicking “go”
		And user enters ten dollars in budget input field
		When user clicks “change budget” button
		Then the amount spent value should be displayed in yellow on the dashboard

	Scenario: Either amount spent and/or budget value is shown in green if under budget 
		Given user is logged in
			|username|cucumber|
			|password|cucumber|
		And user enters “food” into category field
		And ten dollars have been spent on food
		And user selects month of may 
		And user has input the information by clicking “go”
		And user enters fifteen dollars in budget input field
		When user clicks “change budget” button
		Then the amount spent value should be displayed in green on the dashboard

	Scenario: User should be able to see the budget details of any category for any month
		Given user is logged in
			|username|cucumber|
			|password|cucumber|
		And user enters “food” into category field
		And user selects month of may 
		When user has input the information by clicking “go”
		Then the budget details for the month of may should be displayed

	Scenario: User can see amount spend and budget set for any category
		Given user is logged in
			|username|cucumber|
			|password|cucumber|
		And user enters “food” into category field
		And user selects month of may 
		When user has input the information by clicking “go”
		Then the user should see budget and amount spent values on the dashboard

	Scenario: When user logs in they should see that they can set a budget for a category using the budget module rather than the upload module
		Given user is logged in
			|username|cucumber|
			|password|cucumber|
		And user enters “food” into category field
		And user selects month of may 
		And user has input the information by clicking “go”
		And user enters five dollars in budget input field
		When user clicks “change budget” button
		Then the budget for food should be set to five dollars without using upload







