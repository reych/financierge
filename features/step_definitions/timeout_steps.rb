require 'rspec'
require 'selenium-webdriver'
#driver = Selenium::WebDriver.for :firefox

When(/^two minutes of inactivity are up$/) do
  #when user first signs in transaction module is empty
  driver.navigate.to("http://localhost")
  driver.find_element(:id, "login-username").send_keys("renachen@usc.edu")
  driver.find_element(:id, "login-password").send_keys("rc")
  driver.find_element(:id, "login-submit").click
  currentURL = driver.current_url
  expect(currentURL).to eq("http://localhost/mainpage.php")

  #wait little over 2 minutes
  driver.manage.timeouts.implicit_wait = 130
end

Then(/^the user should be redirected to login$/) do
  expect(currentURL).to eq("http://localhost")
end
