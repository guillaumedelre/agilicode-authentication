Feature: User
  In order to use the application
  I need to be able to list users and get detail trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: read a user collection
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/users" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/User","@id":"\/api\/users","@type":"hydra:Collection","hydra:member":[{"@id":"\/api\/users\/1","@type":"User","id":1,"username":"gdelre","refreshToken":"5a4bc728-d1d6-11e8-a8d5-f2801f1b9fd1","enabled":true,"service":false,"privileges":["\/api\/privileges\/1"],"preferences":["\/api\/preferences\/1"]},{"@id":"\/api\/users\/2","@type":"User","id":2,"username":"johndoe","refreshToken":"c6d19778-d1e6-11e8-a8d5-f2801f1b9fd1","enabled":false,"service":false,"privileges":["\/api\/privileges\/2"],"preferences":["\/api\/preferences\/2"]},{"@id":"\/api\/users\/3","@type":"User","id":3,"username":"myApp","refreshToken":"6a122b48-d1d6-11e8-a8d5-f2801f1b9fd1","enabled":true,"service":true,"privileges":["\/api\/privileges\/3"],"preferences":["\/api\/preferences\/3"]}],"hydra:totalItems":3,"hydra:search":{"@type":"hydra:IriTemplate","hydra:template":"\/api\/users{?username}","hydra:variableRepresentation":"BasicRepresentation","hydra:mapping":[{"@type":"IriTemplateMapping","variable":"username","property":"username","required":false}]}}
    """

  Scenario: read a user item
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/users/1" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/User","@id":"\/api\/users\/1","@type":"User","id":1,"username":"gdelre","refreshToken":"5a4bc728-d1d6-11e8-a8d5-f2801f1b9fd1","enabled":true,"service":false,"privileges":["\/api\/privileges\/1"],"preferences":["\/api\/preferences\/1"]}
    """
