Feature: User
  In order to use the application
  I need to be able to update users trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: update a user with minimal payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "username": "updated"
    }
    """
    When I request "/api/users/2" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/User","@id":"\/api\/users\/2","@type":"User","id":2,"username":"updated","enabled":false,"service":false,"privileges":["\/api\/privileges\/2"],"preferences":["\/api\/preferences\/2"]}
    """

  Scenario: enable/disable a user
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    And the request body is:
    """
    {
        "enabled": true
    }
    """
    When I request "/api/users/2" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/User","@id":"\/api\/users\/2","@type":"User","id":2,"username":"johndoe","enabled":true,"service":false,"privileges":["\/api\/privileges\/2"],"preferences":["\/api\/preferences\/2"]}
    """
    And the request body is:
    """
    {
        "enabled": false
    }
    """
    When I request "/api/users/2" using HTTP PUT
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/User","@id":"\/api\/users\/2","@type":"User","id":2,"username":"johndoe","enabled":false,"service":false,"privileges":["\/api\/privileges\/2"],"preferences":["\/api\/preferences\/2"]}
    """
