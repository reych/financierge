Feature: Login authentication

	User must log in to account to access the rest of the website.

	Scenario: Successful login. User enters correct username and password
		Given user is on login page
		Given the following existing user and password:
			|username|christdv@usc.edu|
			|password|christdv|
		When user enters correct username and password
		Then the page should redirect to dashboard

	Scenario: Bad login user enters wrong password for username
		Given the following existing user and password:
			|username|renachen@usc.edu|
			|password|rc|
		When user enters incorrect password
		Then the page should stay on login

	Scenario: Can't log in after four bad log in's before 1 minute is up
		Given the following existing user and password:
			|username|renachen@usc.edu|
			|password|rc|
		When user enters incorrect password
		And user stays on login page after entering wrong password
		And user enters incorrect password
		And user stays on login page after entering wrong password
		And user enters incorrect password
		And user stays on login page after entering wrong password
		And user enters incorrect password
		And user stays on login page after entering wrong password
		And user enters correct username and password
		Then the page should stay on login

	Scenario: Can't log in after four bad log in's before 1 minute is up
		Given user is on login page
		Given the following existing user and password:
			|username|christdv@usc.edu|
			|password|christdv|
		When user enters incorrect password
		And user stays on login page after entering wrong password
		And user enters incorrect password
		And user stays on login page after entering wrong password
		And user enters incorrect password
		And user stays on login page after entering wrong password
		And user enters incorrect password
		And user stays on login page after entering wrong password
		And user logs in with right password after 1 minute
		Then the page should redirect to dashboard
