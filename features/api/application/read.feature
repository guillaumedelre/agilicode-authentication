Feature: Application
  In order to use the application
  I need to be able to list applications and get detail trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: read a application collection
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/applications" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Application","@id":"\/api\/applications","@type":"hydra:Collection","hydra:member":[{"@id":"\/api\/applications\/1","@type":"Application","id":1,"label":"myApp","enabled":true,"permissions":["\/api\/permissions\/1","\/api\/permissions\/2","\/api\/permissions\/3","\/api\/permissions\/4","\/api\/permissions\/5","\/api\/permissions\/6"],"roles":["\/api\/roles\/1","\/api\/roles\/2","\/api\/roles\/3","\/api\/roles\/4"],"privileges":["\/api\/privileges\/1","\/api\/privileges\/2","\/api\/privileges\/3"],"users":["\/api\/users\/1","\/api\/users\/2","\/api\/users\/3"]}],"hydra:totalItems":1}
    """

  Scenario: read a application item
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/applications/1" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Application","@id":"\/api\/applications\/1","@type":"Application","id":1,"label":"myApp","enabled":true,"permissions":["\/api\/permissions\/1","\/api\/permissions\/2","\/api\/permissions\/3","\/api\/permissions\/4","\/api\/permissions\/5","\/api\/permissions\/6"],"roles":["\/api\/roles\/1","\/api\/roles\/2","\/api\/roles\/3","\/api\/roles\/4"],"privileges":["\/api\/privileges\/1","\/api\/privileges\/2","\/api\/privileges\/3"],"users":["\/api\/users\/1","\/api\/users\/2","\/api\/users\/3"]}
    """
