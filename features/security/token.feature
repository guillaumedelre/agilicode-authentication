Feature: Token
  In order to use the application
  I need to be able to  register and get a token.

  Scenario: Clean database
    Given the database is clean

  Scenario: Register a user error case
    Given the "Accept" request header is "application/ld+json"
    Given the following form parameters are set:
      | name     | value |
      | username |       |
      | password |       |
    When I request "/register" using HTTP POST
    Then the response code is 400
    Then the "X-Bearer" response header does not exist

  Scenario: Register a user
    Given the "Accept" request header is "application/ld+json"
    Given the following form parameters are set:
      | name     | value |
      | username | test  |
      | password | test  |
    When I request "/register" using HTTP POST
    Then the response code is 200
    Then the "X-Bearer" response header exists

  Scenario: Generate a token for an existing user
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "username": "test",
        "password": "test"
    }
    """
    When I request "/token" using HTTP POST
    And the response code is 200
    And the JSON node "token" should not be an empty string
