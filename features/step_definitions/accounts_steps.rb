require 'rspec'
require 'selenium-webdriver'
driver = Selenium::WebDriver.for :firefox

Given(/^user is logged in$/) do |table|
    driver.navigate.to("https://localhost/login.html")
    @userCredentials = table.rows_hash
    driver.find_element(:id, "login-username").send_keys(@userCredentials['username'])
    driver.find_element(:id, "login-password").send_keys(@userCredentials['password'])
    driver.find_element(:id, "login-submit").click
    currentURL = driver.current_url
    expect(currentURL).to eq("https://localhost/index.html")
    # table is a Cucumber::Core::Ast::DataTable
end

Given(/^user has an account to delete$/) do
    currentURL = driver.current_url
    puts(currentURL)

    # driver.find_element(:id, "fileToUpload").send_keys("/Users/zhongyag/Developer/financierge/resources/Data.csv")
    driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/Data.csv")
    driver.find_element(:id, "upload-button").click
    #wait for upload 10 seconds
    driver.manage.timeouts.implicit_wait = 20
    #check that account is in list

    #driver.switch_to.alert.accept


    tableBody = driver.find_element(:id, "account-list")
    #randomLine = tableBody.find_element(:tag_name, "tr")
    #existingAccount = randomLine.find_element(:tag_name, "td").text
    existingAccount = tableBody.find_element(:id, "Checking").text
    expect(existingAccount).to eq("Checking")

end

When(/^user uploads the CSV with account delete information$/) do
    # driver.find_element(:id, "fileToUpload").send_keys("/Users/zhongyag/Developer/financierge/resources/DeleteChecking.csv")
    driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/DeleteChecking.csv")
    driver.find_element(:id, "upload-button").click
    #wait for upload 10 seconds
    driver.manage.timeouts.implicit_wait = 10
end

Then(/^the account list should not have the account from the CSV$/) do
    #check that account is not in list
    widget = driver.find_element(:id, "account-list")
    # tableBody = widget.find_element(:tag_name, "table")
    # randomLine = tableBody.find_element(:tag_name, "tr")
    # notDeletedAccount = randomLine.find_element(:tag_name, "td").text
    notDeletedAccount = widget.find_element(:xpath,"//table/tr/td").text
    expect(notDeletedAccount).not_to eq("Checking")
end

When(/^user uploads the CSV with the account$/) do
    # driver.find_element(:id, "fileToUpload").send_keys("/Users/zhongyag/Developer/financierge/resources/Data.csv")
    driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/Data.csv")
    driver.find_element(:id, "upload-button").click
    #wait for upload 10 seconds
    driver.manage.timeouts.implicit_wait = 10

    #driver.switch_to.alert.accept

end

Then(/^the account list should have the account from the CSV$/) do
    #check that account is in list
    tableBody = driver.find_element(:id, "account-list")
    existingAccountThere = tableBody.find_element(:xpath,"//table/tr/td").text
    expect(existingAccountThere).to eq("Checking")

    #delete the account
    # driver.find_element(:id, "fileToUpload").send_keys("/Users/zhongyag/Developer/financierge/resources/DeleteData.csv")
    driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/DeleteData.csv")
    driver.find_element(:id, "upload-button").click
    #wait for upload 3 seconds
    driver.manage.timeouts.implicit_wait = 50

    #driver.switch_to.alert.accept

end

# When(/^user uploads the CSV with accounts for alphabetical order$/) do
#     # driver.find_element(:id, "fileToUpload").send_keys("/Users/zhongyag/Developer/financierge/resources/AddAlphabeticalAccounts.csv")
#     driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/AddAlphabeticalAccounts.csv")
#     driver.find_element(:id, "upload-button").click
#     #wait for upload 10 seconds
#     driver.manage.timeouts.implicit_wait = 50
# end
#
# Then(/^the account list should be in abc order$/) do
#     #Expect apple to be on top of the list
#     tableBody = driver.find_element(:id, "account-list")
#     randomLine = tableBody.find_element(:tag_name, "tr")
#     existingAccount = randomLine.find_element(:tag_name, "td").text
#     expect(existingAccount).to eq("aaaa-apple-Account")
#
#     #delete apple
#     # driver.find_element(:id, "fileToUpload").send_keys("/Users/zhongyag/Developer/financierge/resources/DeleteAlphabeticalAccounts.csv")
#     driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/DeleteAlphabeticalAccounts.csv")
#     driver.find_element(:id, "upload-button").click
#     #wait for upload 3 seconds
#     driver.manage.timeouts.implicit_wait = 50
#
#     #Expect banana to be on top of the list
#     tableBody = driver.find_element(:id, "account-list")
#     randomLine = tableBody.find_element(:tag_name, "tr")
#     banana = randomLine.find_element(:tag_name, "td").text
#     expect(banana).to eq("aaaa-banana-Account")
#
#     #delete banana
#     # driver.find_element(:id, "fileToUpload").send_keys("/Users/zhongyag/Developer/financierge/resources/DeleteAlphabeticalAccounts.csv")
#     driver.find_element(:id, "fileToUpload").send_keys("/home/teamh/financierge/resources/DeleteAlphabeticalAccounts.csv")
#     driver.find_element(:id, "upload-button").click
#     #wait for upload 3 seconds
#     driver.manage.timeouts.implicit_wait = 3
#
#     #log out
#     driver.find_element(:id, "logout").click
#     driver.close
# end
