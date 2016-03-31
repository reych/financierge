Feature: Login authentication

	User must log in to account to access the rest of the website.

	Scenario: Successful login
		Given user is on login page
		Given the following existing user and password:
			|username|renachen@usc.edu|
			|password|rc|
		When user enters correct username and password
		Then the page should redirect to dashboard

	Scenario: Bad login
		Given user is on login page
		Given the following existing user and password:
			|username|renachen@usc.edu|
			|password|rc|
		When user enters incorrect password
		Then the page should stay on login

	Scenario: Can't log in after four bad log in's before 1 minute is up
		Given user is on login page
		Given the following existing user and password:
			|username|renachen@usc.edu|
			|password|rc|
		When user enters incorrect password 4 times
		And user immediately tries to log in with right password
		Then the page should stay on login

	Scenario: Can login after four bad log in's after 1 minute
		Given user is on login page
		Given the following existing user and password:
			|username|renachen@usc.edu|
			|password|rc|
		When user enters incorrect password 4 times
		And user with right password after 1 minute
		Then the page should redirect to dashboard
