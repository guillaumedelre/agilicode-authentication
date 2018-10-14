Feature: Preference
  In order to use the application
  I need to be able to list preferences and get detail trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: read a preference collection
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/preferences" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Preference","@id":"\/api\/preferences","@type":"hydra:Collection","hydra:member":[{"@id":"\/api\/preferences\/1","@type":"Preference","id":1,"application":"\/api\/applications\/1","user":"\/api\/users\/1","data":[]},{"@id":"\/api\/preferences\/2","@type":"Preference","id":2,"application":"\/api\/applications\/1","user":"\/api\/users\/2","data":[]},{"@id":"\/api\/preferences\/3","@type":"Preference","id":3,"application":"\/api\/applications\/1","user":"\/api\/users\/3","data":[]}],"hydra:totalItems":3}
    """

  Scenario: read a preference item
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/preferences/1" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/Preference","@id":"\/api\/preferences\/1","@type":"Preference","id":1,"application":"\/api\/applications\/1","user":"\/api\/users\/1","data":[]}
    """
