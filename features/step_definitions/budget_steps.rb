require 'rspec'
require 'selenium-webdriver'
driver = Selenium::WebDriver.for :firefox

Given(/^user enters “food” into category field$/) do

end

Given(/^user selects month of may$/) do

end

Given(/^user has input the information by clicking “go”$/) do

end

Given(/^user enters five dollars in budget input field$/) do

end

When(/^user clicks “change budget” buttonThen the budget for food should be set to five dollars$/) do

end

Given(/^ten dollars have been spent on food$/) do

end

When(/^user clicks “change budget” button$/) do

end

Then(/^the amount spent value should be displayed in red on the dashboard$/) do
  driver.find_element(:id, "budgety").click
end

Given(/^user enters ten dollars in budget input field$/) do

end

Then(/^the amount spent value should be displayed in yellow on the dashboard$/) do
  driver.find_element(:id, "budgety").click
end

Given(/^user enters fifteen dollars in budget input field$/) do
end

Then(/^the amount spent value should be displayed in green on the dashboard$/) do
  driver.find_element(:id, "budgety").click
end

Then(/^the budget details for the month of may should be displayed$/) do
  driver.find_element(:id, "budgety").click
end

Then(/^the user should see budget and amount spent values on the dashboard$/) do
  driver.find_element(:id, "budgety").click
end

Then(/^the budget for food should be set to five dollars without using upload$/) do
  driver.find_element(:id, "budgety").click
end
