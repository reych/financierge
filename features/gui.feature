Feature: Mobile Compatibility
	Scenario: When user resizes the webpage they should see that it has a mobile view
		Given user is logged in
			|username|cucumber|
	  		|password|cucumber|
		When user drags webpage down too mobile dimension
		Then the page should dynamically change to mobile layout
