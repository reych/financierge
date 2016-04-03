require 'rspec'
require 'selenium-webdriver'
# driver = Selenium::WebDriver.for :firefox

Given(/^user is logged in$/) do |table|
  driver.navigate.to("http://localhost/login.html")
  @userCredentials = table.rows_hash
  driver.find_element(:id, "login-username").send_keys(@userCredentials['username'])
  driver.find_element(:id, "login-password").send_keys(@userCredentials['password'])
  driver.find_element(:id, "login-submit").click
  currentURL = driver.current_url
  expect(currentURL).to eq("http://localhost/index.html")
  # table is a Cucumber::Core::Ast::DataTable
end

Given(/^user has an account to delete$/) do
 driver.find_element(:id, "fileToUpload").send_keys("../../resources/data.csv")
 driver.find_element(:id, "upload-button").click
 #wait for upload 10 seconds
 driver.manage.timeouts.implicit_wait = 10
 #check that account is in list

 driver.switch_to.alert.accept


 tableBody = driver.find_element(:id, "account-list")
 randomLine = tableBody.find_element(:tag_name, "tr")
 existingAccount = randomLine.find_element(:tag_name, "td").text
 expect(existingAccount).to eq("Checking")

end

When(/^user uploads the CSV with account delete information$/) do
 driver.find_element(:id, "fileToUpload").send_keys("../../resources/jeffsdelete.csv")
 driver.find_element(:id, "upload-button").click
 #wait for upload 10 seconds
 driver.manage.timeouts.implicit_wait = 10
end

Then(/^the account list should not have the account from the CSV$/) do
 #check that account is not in list
 tableBody = driver.find_element(:id, "account-list")
 randomLine = tableBody.find_element(:tag_name, "tr")
 notDeletedAccount = randomLine.find_element(:tag_name, "td").text
 expect(notDeletedAccount).not_to eq("Checking")
end

When(/^user uploads the CSV with the account$/) do
 driver.find_element(:id, "fileToUpload").send_keys("../../resources/data.csv")
 driver.find_element(:id, "upload-button").click
 #wait for upload 10 seconds
 driver.manage.timeouts.implicit_wait = 10

 driver.switch_to.alert.accept

end

Then(/^the account list should have the account from the CSV$/) do
  #check that account is in list
 tableBody = driver.find_element(:id, "account-list")
 randomLine = tableBody.find_element(:tag_name, "tr")
 existingAccountThere = randomLine.find_element(:tag_name, "td").text
 expect(existingAccountThere).to eq("Checking")

 #delete the account
 driver.find_element(:id, "fileToUpload").send_keys("../../resources/jeffsdelete.csv")
 driver.find_element(:id, "upload-button").click
 #wait for upload 3 seconds
 driver.manage.timeouts.implicit_wait = 3

 driver.switch_to.alert.accept

end

When(/^user uploads the CSV with accounts for alphabetical order$/) do
 driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/addalphabeticalaccounts.csv")
 driver.find_element(:id, "upload-button").click
 #wait for upload 10 seconds
 driver.manage.timeouts.implicit_wait = 10
end

Then(/^the account list should be in abc order$/) do
 #Expect apple to be on top of the list
 tableBody = driver.find_element(:id, "account-list")
 randomLine = tableBody.find_element(:tag_name, "tr")
 existingAccount = randomLine.find_element(:tag_name, "td").text
 expect(existingAccount).to eq("aaaa apple Account")

 #delete apple
 driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/deletealphabeticalaccounts.csv")
 driver.find_element(:id, "upload-button").click
 #wait for upload 3 seconds
 driver.manage.timeouts.implicit_wait = 3

 #Expect banana to be on top of the list
 tableBody = driver.find_element(:id, "account-list")
 randomLine = tableBody.find_element(:tag_name, "tr")
 banana = randomLine.find_element(:tag_name, "td").text
 expect(banana).to eq("aaaa banana Account")

 #delete banana
 driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/deletealphabeticalaccounts (all).csv")
 driver.find_element(:id, "upload-button").click
 #wait for upload 3 seconds
 driver.manage.timeouts.implicit_wait = 3

 driver.quit
end
