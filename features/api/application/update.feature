Feature: Application
  In order to use the application
  I need to be able to update applications trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: update a application with minimal payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "label": "updated"
    }
    """
    When I request "/api/applications/1" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Application","@id":"\/api\/applications\/1","@type":"Application","id":1,"label":"updated","enabled":true}
    """

  Scenario: disable/enable an application
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "enabled": false
    }
    """
    When I request "/api/applications/1" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Application","@id":"\/api\/applications\/1","@type":"Application","id":1,"label":"myApp","enabled":false}
    """
    And the request body is:
    """
    {
        "enabled": true
    }
    """
    When I request "/api/applications/1" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Application","@id":"\/api\/applications\/1","@type":"Application","id":1,"label":"myApp","enabled":true}
    """
