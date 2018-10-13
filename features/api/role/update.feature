Feature: Role
  In order to use the application
  I need to be able to update roles trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: update a role with minimal payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "label": "updated"
    }
    """
    When I request "/api/roles/2" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Role","@id":"\/api\/roles\/2","@type":"Role","id":2,"label":"updated","application":"\/api\/applications\/1"}
    """
