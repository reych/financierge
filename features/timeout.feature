Feature: Timeout

  Scenario: User automatically logs out after 2 min of inactivity
    Given user is logged in
    When two minutes of inactivity are up
    Then the user should be redirected to login