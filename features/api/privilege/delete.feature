Feature: Privilege
  In order to use the application
  I need to be able to delete privileges trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    crud-api.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "gdelre"
    And I save it into "XBearer"

  Scenario: delete a privilege
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/privileges/2" using HTTP DELETE
    Then the response code is 204
