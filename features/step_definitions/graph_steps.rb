require 'rspec'
require 'selenium-webdriver'
driver = Selenium::WebDriver.for :firefox

When(/^user clicks on an account in the account list$/) do
end

When(/^the account is not toggled$/) do

end

Then(/^the graph line for that account should show up\.$/) do
  
end

Then(/^it should be a different color than all the other$/) do
end

Then(/^the graph line for that account should show up and be the same color as the button color$/) do

end

Given(/^the graph exists$/) do
  
end

When(/^user looks at dash$/) do
  
end

Then(/^they should see liability, assets, and net worth line on the graph$/) do

end

When(/^user selects a specific calendar date$/) do
  
end

Then(/^the graph should adjust to the correct interval$/) do
	driver.find_element(:id, "graphy").click
end

Given(/^an account has already been toggled$/) do
  
end

When(/^user clicks on account that has already been toggled$/) do
  
end

Then(/^the line should not be in the graph$/) do

end

When(/^user views the dash$/) do
  
end

Then(/^it should be defaulted into a three month interval$/) do
	driver.find_element(:id, "graphy").click
end
