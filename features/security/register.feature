Feature: Security register
  In order to use the application
  As a user
  I need to be able to register.

  Background: Clean database
    Given the following fixtures are loaded:
    """
    security-token.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the "Accept" request header is "application/ld+json"

  Scenario: Register a user error case
    Given the following form parameters are set:
      | name     | value |
      | username |       |
      | password |       |
    When I request "/register" using HTTP POST
    Then the response code is 400

  Scenario: Register a user
    Given the following form parameters are set:
      | name     | value |
      | username | test  |
      | password | test  |
    When I request "/register" using HTTP POST
    Then the response code is 200
