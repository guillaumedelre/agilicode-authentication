Feature: User
  In order to use the application
  I need to be able to update users trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    api/crud-user.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "admin"
    And I save it into "XBearer"

  Scenario: update a user with minimal payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "username": "updated"
    }
    """
    When I request "/api/users/2" using HTTP PUT
    Then the response code is 200
    And the JSON node "$.@type" should be equal to "User"
    And the JSON node "$.@id" should be equal to "/api/users/2"
    And the JSON node "$.username" should be equal to "updated"
    And the JSON node "$.password" should not be an empty string
