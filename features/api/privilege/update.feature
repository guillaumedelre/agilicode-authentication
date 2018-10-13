Feature: Privilege
  In order to use the application
  I need to be able to update privileges trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: update a privilege with minimal payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    When I request "/api/privileges/2" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Privilege","@id":"\/api\/privileges\/2","@type":"Privilege","id":2,"user":"\/api\/users\/2","application":"\/api\/applications\/1","roles":["\/api\/roles\/2"]}
    """
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "roles": ["/api/roles/2", "/api/roles/3"]
    }
    """
    When I request "/api/privileges/2" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Privilege","@id":"\/api\/privileges\/2","@type":"Privilege","id":2,"user":"\/api\/users\/2","application":"\/api\/applications\/1","roles":["\/api\/roles\/2","\/api\/roles\/3"]}
    """
