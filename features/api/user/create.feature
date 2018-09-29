Feature: User
  In order to use the application
  I need to be able to create users trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    api/crud-user.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "admin"
    And I save it into "XBearer"

  Scenario: create a user with empty payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {}
    """
    When I request "/api/users" using HTTP POST
    Then the response code is 400

  Scenario: create a user with minimal payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "username": "test",
        "password": "test"
    }
    """
    When I request "/api/users" using HTTP POST
    Then the response code is 201
    And the JSON node "$.@type" should be equal to "User"
    And the JSON node "$.username" should be equal to "test"
    And the JSON node "$.password" should not be an empty string

  Scenario: check unicity on username
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "username": "test",
        "password": "test"
    }
    """
    When I request "/api/users" using HTTP POST
    Then the response code is 201
    Given the request body is:
    """
    {
        "username": "test",
        "password": "test"
    }
    """
    When I request "/api/users" using HTTP POST
    Then the response code is 400
    And the JSON node "$.@type" should be equal to "ConstraintViolationList"
    And the JSON node "$.hydra:description" should be equal to "username: This value is already used."
    And the JSON node "$.violations" should have 1 elements
    And the JSON node "$.violations.0.propertyPath" should be equal to "username"
    And the JSON node "$.violations.0.message" should be equal to "This value is already used."
