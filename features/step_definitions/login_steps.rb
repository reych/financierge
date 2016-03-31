require 'rspec'
require 'selenium-webdriver'
#driver = Selenium::WebDriver.for :firefox

Given(/^user is on login page$/) do
	driver.navigate.to("http://localhost")
  #wait = Selenium::WebDriver::Wait.new(:timeout => 10)
  #wait.until {driver.title.downcase.start_with? "login"}
  #expect(page).to have_selector('login-box')
end
Given(/^the following existing user and password:$/) do |table|
  # table is a Cucumber::Core::Ast::DataTable
  @userCredentials = table.rows_hash

end

# Good password
When(/^user enters correct username and password$/) do
  driver.find_element(:id, "login-username").send_keys(@userCredentials['username'])
  driver.find_element(:id, "login-password").send_keys(@userCredentials['password'])

end

Then(/^the page should redirect to dashboard$/) do
  driver.find_element(:id, "login-submit").click
  currentURL = driver.current_url
  expect(currentURL).to eq("http://localhost/mainpage.php")
  driver.find_element(:id, "logout").click
end

# For bad password
When(/^user enters incorrect password$/) do
  driver.find_element(:id, "login-username").send_keys(@userCredentials['username'])
  badPassword = @userCredentials['password']
  badPassword.reverse!
  driver.find_element(:id, "login-password").send_keys(badPassword)
end

Then(/^the page should stay on login$/) do
  loginURL = driver.current_url
  driver.find_element(:id, "login-submit").click
  currentURL = driver.current_url
  expect(currentURL).to eq(loginURL)

  driver.close
end
