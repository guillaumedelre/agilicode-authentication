Feature: Preference
  In order to use the application
  I need to be able to create preferences trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: create a preference with empty payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {}
    """
    When I request "/api/preferences" using HTTP POST
    Then the response code is 400
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"application: This value should not be null.\nuser: This value should not be null.","violations":[{"propertyPath":"application","message":"This value should not be null."},{"propertyPath":"user","message":"This value should not be null."}]}
    """

  Scenario: create a preference with minimal payload
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
        "data": []
    }
    """
    When I request "/api/preferences" using HTTP POST
    Then the response code is 201
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Preference","@id":"\/api\/preferences\/4","@type":"Preference","id":4,"application":"\/api\/applications\/1","user":"\/api\/users\/4","data":[]}
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
        "data": []
    }
    """
    When I request "/api/preferences" using HTTP POST
    Then the response code is 201
    And the request body is:
    """
    {
        "user": "/api/users/4",
        "application": "/api/applications/1",
        "data": []
    }
    """
    When I request "/api/preferences" using HTTP POST
    Then the response code is 400
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"user: This value is already used.","violations":[{"propertyPath":"user","message":"This value is already used."}]}
    """
