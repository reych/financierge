Feature: Sort transactions for an account by date, catetory, and amount

	Transactions displayed in the transactions widget can be sorted by date, category, or amount. These buttons will be the corresponding table headers for each column. 

	Scenario: When the user clicks on the date button for the transactions, then they will be sorted by date.
		Given account transactions are displayed
		When the user clicks the date button
		Then the transactions should be sorted from earliest date to latest

	Scenario: When the user clicks the category button for the transactions, then they will be sorted by category.
		Given account transactions are displayed
		When(/^the user clicks the category button$/)
		Then(/^the transactions should be sorted in alphabetical order according to category$/)

	Scenario: When the user clicks the amount button for transactions, then they will be sorted by amount.
		Given account transactions are displayed
		When the user clicks the amount button
		Then the transactions should be sorted from least amount to greatest