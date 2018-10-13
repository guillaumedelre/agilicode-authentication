Feature: Permission
  In order to use the application
  I need to be able to update permissions trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: update a permission with minimal payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "label": "do something else"
    }
    """
    When I request "/api/permissions/2" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Permission","@id":"\/api\/permissions\/2","@type":"Permission","id":2,"label":"do something else","application":"\/api\/applications\/1"}
    """
