Feature: Permission
  In order to use the application
  I need to be able to create permissions trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: create a permission with empty payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {}
    """
    When I request "/api/permissions" using HTTP POST
    Then the response code is 400
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"label: This value should not be blank.","violations":[{"propertyPath":"label","message":"This value should not be blank."}]}
    """

  Scenario: create a permission with minimal payload for application myApp
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "label": "do something",
        "application": "/api/applications/1"
    }
    """
    When I request "/api/permissions" using HTTP POST
    Then the response code is 201
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Permission","@id":"\/api\/permissions\/7","@type":"Permission","id":7,"label":"do something","application":"\/api\/applications\/1"}
    """

  Scenario: check unicity on label and application
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "label": "do something",
        "application": "/api/applications/1"
    }
    """
    When I request "/api/permissions" using HTTP POST
    Then the response code is 201
    Given the request body is:
    """
    {
        "label": "do something",
        "application": "/api/applications/1"
    }
    """
    When I request "/api/permissions" using HTTP POST
    Then the response code is 400
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"label: This value is already used.","violations":[{"propertyPath":"label","message":"This value is already used."}]}
    """
