Feature: Preference
  In order to use the application
  I need to be able to update preferences trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: update a preference with minimal payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    When I request "/api/preferences/2" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Preference","@id":"\/api\/preferences\/2","@type":"Preference","id":2,"application":"\/api\/applications\/1","user":"\/api\/users\/2","data":[]}
    """
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "data": [{"property": "value"}]
    }
    """
    When I request "/api/preferences/2" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Preference","@id":"\/api\/preferences\/2","@type":"Preference","id":2,"application":"\/api\/applications\/1","user":"\/api\/users\/2","data":[{"property":"value"}]}
    """
