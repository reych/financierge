require 'rspec'
require 'selenium-webdriver'
#driver = Selenium::WebDriver.for :firefox

Given(/^account transactions are displayed$/) do
    #when user first signs in transaction module is empty
    driver.navigate.to("http://localhost")
    driver.find_element(:id, "login-username").send_keys("renachen@usc.edu")
    driver.find_element(:id, "login-password").send_keys("rc")
    driver.find_element(:id, "login-submit").click
    currentURL = driver.current_url
    expect(currentURL).to eq("http://localhost/mainpage.php")

    driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/data (test).csv")
    driver.find_element(:id, "upload-button").click
    #wait for upload 10 seconds
    driver.manage.timeouts.implicit_wait = 10

    #actual click
    driver.find_element(:id, "Checking").click
    driver.find_element(:id, "date-Checking").click
end

When(/^the user clicks the date button$/) do
    driver.find_element(:id, "date-Checking").click
end

Then(/^the transactions should be sorted from earliest date to latest\.$/) do
    driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/deleteall.csv")
    driver.find_element(:id, "upload-button").click
end

When(/^the user clicks the category button$/) do
    driver.find_element(:id, "category-Checking").click
end

Then(/^the transactions should be sorted in alphabetical order according to category$/) do
    driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/deleteall.csv")
    driver.find_element(:id, "upload-button").click
end

When(/^the user clicks the amount button$/) do
    driver.find_element(:id, "amount-Checking").click
end

Then(/^the transactions should be sorted from least amount to greatest\.$/) do
    driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/deleteall.csv")
    driver.find_element(:id, "upload-button").click
end
