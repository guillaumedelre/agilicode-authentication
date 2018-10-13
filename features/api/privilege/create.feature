Feature: Privilege
  In order to use the application
  I need to be able to create privileges trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: create a privilege with empty payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {}
    """
    When I request "/api/privileges" using HTTP POST
    Then the response code is 400
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"roles: This collection should contain 1 element or more.","violations":[{"propertyPath":"roles","message":"This collection should contain 1 element or more."}]}
    """

  Scenario: create a privilege with minimal payload
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
    And the request body is:
    """
    {
        "user": "/api/users/4",
        "application": "/api/applications/1",
        "roles": ["/api/roles/4"]
    }
    """
    When I request "/api/privileges" using HTTP POST
    Then the response code is 201
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Privilege","@id":"\/api\/privileges\/4","@type":"Privilege","id":4,"user":"\/api\/users\/4","application":"\/api\/applications\/1","roles":["\/api\/roles\/4"]}
    """

  Scenario: check unicity on user and application
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
    And the request body is:
    """
    {
        "user": "/api/users/4",
        "application": "/api/applications/1",
        "roles": ["/api/roles/4"]
    }
    """
    When I request "/api/privileges" using HTTP POST
    Then the response code is 201
    And the request body is:
    """
    {
        "user": "/api/users/4",
        "application": "/api/applications/1",
        "roles": ["/api/roles/4"]
    }
    """
    When I request "/api/privileges" using HTTP POST
    Then the response code is 400
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"user: This value is already used.","violations":[{"propertyPath":"user","message":"This value is already used."}]}
    """
