Feature: Privilege
  In order to use the application
  I need to be able to list privileges and get detail trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: read a privilege collection
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/privileges" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Privilege","@id":"\/api\/privileges","@type":"hydra:Collection","hydra:member":[{"@id":"\/api\/privileges\/1","@type":"Privilege","id":1,"user":"\/api\/users\/1","application":"\/api\/applications\/1","roles":["\/api\/roles\/1","\/api\/roles\/3"]},{"@id":"\/api\/privileges\/2","@type":"Privilege","id":2,"user":"\/api\/users\/2","application":"\/api\/applications\/1","roles":["\/api\/roles\/2"]},{"@id":"\/api\/privileges\/3","@type":"Privilege","id":3,"user":"\/api\/users\/3","application":"\/api\/applications\/1","roles":["\/api\/roles\/4"]}],"hydra:totalItems":3}
    """

  Scenario: read a privilege item
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/privileges/1" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Privilege","@id":"\/api\/privileges\/1","@type":"Privilege","id":1,"user":"\/api\/users\/1","application":"\/api\/applications\/1","roles":["\/api\/roles\/1","\/api\/roles\/3"]}
    """
