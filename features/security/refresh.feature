Feature: Security refresh
  In order to use the application
  As a user
  I need to be able to refresh a token.

  Background: Clean database
    Given the following fixtures are loaded:
    """
    security-token.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the "Accept" request header is "application/ld+json"

  Scenario: Refresh the token when it expires
    When I request "/refresh/5a4bc728-d1d6-11e8-a8d5-f2801f1b9fd1" using HTTP GET
    And the response code is 200
    And the JSON node "token" should not be an empty string
    And the "X-Refresh-Token" response header exists

  Scenario: Refresh the token when it expires with bad token
    When I request "/refresh/5a4bc728-d1d6-11e8-a8d5-f2801f1b9aze" using HTTP GET
    And the response code is 404
    When I request "/refresh/ " using HTTP GET
    And the response code is 404

