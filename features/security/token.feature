Feature: Security token
  In order to use the application
  As a user
  I need to be able to get a token.

  Scenario: Clean database
    Given the following fixtures are loaded:
    """
    security-token.yaml
    """
    And the "X-APP-ENV" request header is "test"

  Scenario: Generate a token for an existing user
    And the "X-APP-ENV" request header is "test"
    Given the "Accept" request header is "application/ld+json"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "username": "gdelre",
        "password": "gdelre"
    }
    """
    When I request "/token" using HTTP POST
    And the response code is 200
    And the JSON node "token" should not be an empty string
    And the "X-Refresh-Token" response header exists
