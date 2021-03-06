require 'rspec'
require 'selenium-webdriver'
driver = Selenium::WebDriver.for :firefox

Given(/^user is in dashboard$/) do
    driver.navigate.to("https://localhost/login.html")
    driver.find_element(:id, "login-username").send_keys('christdv@usc.edu')
    driver.find_element(:id, "login-password").send_keys('christdv')
    driver.find_element(:id, "login-submit").click
    currentURL = driver.current_url
    expect(currentURL).to eq("https://localhost/index.html")
    puts "DON'T MOVE MOUSE OR PRESS A KEY, CHECKING FOR INACTIVITY"
    # table is a Cucumber::Core::Ast::DataTable
end

When(/^two minutes of inactivity are up$/) do
    sleep(130.0)
end

Then(/^the user should be redirected to login$/) do
    expect(driver.current_url).to eq("https://localhost/login.html")
end
