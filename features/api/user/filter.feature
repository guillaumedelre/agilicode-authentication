Feature: User
  In order to use the application
  I need to be able to filter users trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: create a user with empty payload
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/users?username=gdelre" using HTTP GET
    Then the response code is 200
    And the response body is:
    """
    {"@context":"\/api\/contexts\/User","@id":"\/api\/users","@type":"hydra:Collection","hydra:member":[{"@id":"\/api\/users\/1","@type":"User","id":1,"username":"gdelre","enabled":true,"service":false,"privileges":["\/api\/privileges\/1"]}],"hydra:totalItems":1,"hydra:view":{"@id":"\/api\/users?username=gdelre","@type":"hydra:PartialCollectionView"},"hydra:search":{"@type":"hydra:IriTemplate","hydra:template":"\/api\/users{?username}","hydra:variableRepresentation":"BasicRepresentation","hydra:mapping":[{"@type":"IriTemplateMapping","variable":"username","property":"username","required":false}]}}
    """
