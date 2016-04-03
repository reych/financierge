require 'rspec'
require 'selenium-webdriver'
# driver = Selenium::WebDriver.for :firefox

Given(/^nothing is yet displayed in the transaction box$/) do |table|
  #when user first signs in transaction module is empty
  driver.navigate.to("http://localhost/login.html")
  @userCredentials = table.rows_hash
  driver.find_element(:id, "login-username").send_keys(@userCredentials['username'])
  driver.find_element(:id, "login-password").send_keys(@userCredentials['password'])
  driver.find_element(:id, "login-submit").click
  currentURL = driver.current_url
  expect(currentURL).to eq("http://localhost/index.html")
end

When(/^the user clicks on Checking$/) do
	driver.find_element(:id, "fileToUpload").send_keys("../../resources/Data.csv")
    driver.find_element(:id, "upload-button").click
    #wait for upload 10 seconds

    driver.manage.timeouts.implicit_wait = 10

    #driver.switch_to.alert.accept

    #actual click
 	driver.find_element(:id, "Checking").click
end

Then(/^all the transactions for Checking are displayed in the transactions widget\.$/) do
    driver.find_element(:id, "tab-Checking").click
    driver.find_element(:id, "content-Checking")
    driver.find_element(:id, "fileToUpload").send_keys("../../resources/DeleteData.csv")
    driver.find_element(:id, "upload-button").click
end

Given(/^there is already an account displayed in the transactions widget$/) do |table|
  #when user first signs in transaction module is empty
  driver.navigate.to("http://localhost/login.html")
  @userCredentials = table.rows_hash
  driver.find_element(:id, "login-username").send_keys(@userCredentials['username'])
  driver.find_element(:id, "login-password").send_keys(@userCredentials['password'])
  driver.find_element(:id, "login-submit").click
  currentURL = driver.current_url
  expect(currentURL).to eq("http://localhost/index.html")

  driver.find_element(:id, "fileToUpload").send_keys("../../resources/Data.csv")
  driver.find_element(:id, "upload-button").click
  #wait for upload 10 seconds
  driver.manage.timeouts.implicit_wait = 40

  #actual click
  driver.find_element(:id, "Checking").click
end

When(/^User clicks Savings$/) do
  driver.find_element(:id, "Savings").click
end

Then(/^Savings transaction info is displayed$/) do
  driver.find_element(:id, "tab-Savings").click

  driver.find_element(:id, "content-Savings")
  driver.find_element(:id, "fileToUpload").send_keys("../../resources/DeleteData.csv")
  driver.find_element(:id, "upload-button").click

  #log out
  driver.find_element(:id, "logout").click
  driver.close

end
