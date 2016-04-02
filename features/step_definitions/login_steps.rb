require 'rspec'
require 'selenium-webdriver'
driver = Selenium::WebDriver.for :firefox

Given(/^user is on login page$/) do
	driver.navigate.to("http://localhost/login.html")
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
  expect(currentURL).to eq("http://localhost/index.html")
  driver.find_element(:id, "logout").click
end

# For bad password
When(/^user enters incorrect password$/) do

  driver.find_element(:id, "login-username").send_keys(@userCredentials['username'])
  badPassword = @userCredentials['password']
  badPassword.reverse!
  driver.find_element(:id, "login-password").send_keys(badPassword)
end

# For bad password 4 times
When(/^user stays on login page after entering wrong password$/) do
  # driver.switch_to.alert.accept rescue Selenium::WebDriver::Error::UnhandledAlertError
  begin
  driver.switch_to.alert.accept
  rescue Selenium::WebDriver::Error::NoSuchAlertError
  rescue Selenium::WebDriver::Error::UnhandledAlertError
  # a = 1
  else
  # a = 1
  end


  loginURL = driver.current_url
  driver.find_element(:id, "login-submit").click
  currentURL = driver.current_url
  expect(currentURL).to eq(loginURL)
end

# Good password after 1 minute wait
When(/^user logs in with right password after 1 minute$/) do
  driver.find_element(:id, "login-username").send_keys(@userCredentials['username'])
  driver.find_element(:id, "login-password").send_keys(@userCredentials['password'])
  #wait longer for timeout 65 sections
  driver.manage.timeouts.implicit_wait = 65
end

Then(/^the page should stay on login$/) do
  # driver.sendKeys(Keys.RETURN)
  begin
  driver.switch_to.alert.accept
  rescue Selenium::WebDriver::Error::NoSuchAlertError
  rescue Selenium::WebDriver::Error::UnhandledAlertError
	#    a = 1
  else
	#    a = 1
  end


  loginURL = driver.current_url
  driver.find_element(:id, "login-submit").click

  currentURL = driver.current_url

  expect(currentURL).to eq(loginURL)

  driver.close
end
