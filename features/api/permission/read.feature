Feature: Permission
  In order to use the application
  I need to be able to list permissions and get detail trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: read a permission collection
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/permissions" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Permission","@id":"\/api\/permissions","@type":"hydra:Collection","hydra:member":[{"@id":"\/api\/permissions\/1","@type":"Permission","id":1,"label":"create_something","application":"\/api\/applications\/1"},{"@id":"\/api\/permissions\/2","@type":"Permission","id":2,"label":"read_something","application":"\/api\/applications\/1"},{"@id":"\/api\/permissions\/3","@type":"Permission","id":3,"label":"update_something","application":"\/api\/applications\/1"},{"@id":"\/api\/permissions\/4","@type":"Permission","id":4,"label":"delete_something","application":"\/api\/applications\/1"},{"@id":"\/api\/permissions\/5","@type":"Permission","id":5,"label":"clone_share","application":"\/api\/applications\/1"},{"@id":"\/api\/permissions\/6","@type":"Permission","id":6,"label":"comment_share","application":"\/api\/applications\/1"}],"hydra:totalItems":6}
    """

  Scenario: read a permission item
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/permissions/1" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Permission","@id":"\/api\/permissions\/1","@type":"Permission","id":1,"label":"create_something","application":"\/api\/applications\/1"}
    """
