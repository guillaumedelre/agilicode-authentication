Feature: Application
  In order to use the application
  I need to be able to create applications trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: create a application with empty payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {}
    """
    When I request "/api/applications" using HTTP POST
    Then the response code is 400
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"label: This value should not be blank.","violations":[{"propertyPath":"label","message":"This value should not be blank."}]}
    """

  Scenario: create a application with minimal payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "label": "testApp"
    }
    """
    When I request "/api/applications" using HTTP POST
    Then the response code is 201
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Application","@id":"\/api\/applications\/2","@type":"Application","id":2,"label":"testApp","enabled":false}
    """

  Scenario: check unicity on applicationname
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "label": "testApp"
    }
    """
    When I request "/api/applications" using HTTP POST
    Then the response code is 201
    Given the request body is:
    """
    {
        "label": "testApp"
    }
    """
    When I request "/api/applications" using HTTP POST
    Then the response code is 400
    And the response body is:
    """
    {"@context":"\/api\/contexts\/ConstraintViolationList","@type":"ConstraintViolationList","hydra:title":"An error occurred","hydra:description":"label: This value is already used.","violations":[{"propertyPath":"label","message":"This value is already used."}]}
    """
