require 'rspec'
require 'selenium-webdriver'
driver = Selenium::WebDriver.for :firefox

Given(/^account transactions are displayed$/) do
end

When(/^the user clicks the date button$/) do
end

Then(/^the transactions should be sorted from earliest date to latest\.$/) do
	driver.close
end

When(/^the user clicks the category button$/) do
end

Then(/^the transactions should be sorted in alphabetical order according to category$/) do
    driver.close
end

When(/^the user clicks the amount button$/) do
end

Then(/^the transactions should be sorted from least amount to greatest\.$/) do
    driver.close
end
