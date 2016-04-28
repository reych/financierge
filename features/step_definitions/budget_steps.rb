require 'rspec'
require 'selenium-webdriver'
driver = Selenium::WebDriver.for :firefox

Given(/^user enters “food” into category field$/) do
	driver.manage.timeouts.implicit_wait = 1
	currentURL = driver.current_url
end

Given(/^user selects month of may$/) do
	driver.manage.timeouts.implicit_wait = 1
	currentURL = driver.current_url
end

Given(/^user has input the information by clicking “go”$/) do
	driver.manage.timeouts.implicit_wait = 1
end

Given(/^user enters five dollars in budget input field$/) do
	driver.manage.timeouts.implicit_wait = 1
end

When(/^user clicks “change budget” buttonThen the budget for food should be set to five dollars$/) do
	currentURL = driver.current_url
end

Given(/^ten dollars have been spent on food$/) do
	currentURL = driver.current_url
end

When(/^user clicks “change budget” button$/) do
	driver.manage.timeouts.implicit_wait = 1
end

Then(/^the amount spent value should be displayed in red on the dashboard$/) do
	currentURL = driver.current_url
end

Given(/^user enters ten dollars in budget input field$/) do
	driver.manage.timeouts.implicit_wait = 1
end

Then(/^the amount spent value should be displayed in yellow on the dashboard$/) do

end

Given(/^user enters fifteen dollars in budget input field$/) do
	driver.manage.timeouts.implicit_wait = 1
end

Then(/^the amount spent value should be displayed in green on the dashboard$/) do
	driver.manage.timeouts.implicit_wait = 1
end

Then(/^the budget details for the month of may should be displayed$/) do
	driver.manage.timeouts.implicit_wait = 1
end

Then(/^the user should see budget and amount spent values on the dashboard$/) do
	currentURL = driver.current_url
end

Then(/^the budget for food should be set to five dollars without using upload$/) do
	currentURL = driver.current_url
end
