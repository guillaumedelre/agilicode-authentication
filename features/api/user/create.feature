Feature: User
  In order to use the application
  I need to be able to create users trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
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
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"username: This value should not be blank.","violations":[{"propertyPath":"username","message":"This value should not be blank."}]}
    """

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
    And the response body is:
    """
    {"@context":"\/api\/contexts\/User","@id":"\/api\/users\/4","@type":"User","id":4,"username":"test","enabled":false,"service":false,"privileges":[],"preferences":[]}
    """

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
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"username: This value is already used.","violations":[{"propertyPath":"username","message":"This value is already used."}]}
    """
