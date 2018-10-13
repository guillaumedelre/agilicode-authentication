Feature: Role
  In order to use the application
  I need to be able to list roles and get detail trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: read a role collection
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/roles" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Role","@id":"\/api\/roles","@type":"hydra:Collection","hydra:member":[{"@id":"\/api\/roles\/1","@type":"Role","id":1,"label":"administrator","application":"\/api\/applications\/1"},{"@id":"\/api\/roles\/2","@type":"Role","id":2,"label":"user","application":"\/api\/applications\/1"},{"@id":"\/api\/roles\/3","@type":"Role","id":3,"label":"test","application":"\/api\/applications\/1"},{"@id":"\/api\/roles\/4","@type":"Role","id":4,"label":"service","application":"\/api\/applications\/1"}],"hydra:totalItems":4}
    """

  Scenario: read a role item
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/roles/1" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Role","@id":"\/api\/roles\/1","@type":"Role","id":1,"label":"administrator","application":"\/api\/applications\/1"}
    """
