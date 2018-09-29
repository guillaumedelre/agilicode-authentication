Feature: User
  In order to use the application
  I need to be able to delete users trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    api/crud-user.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "admin"
    And I save it into "XBearer"

  Scenario: delete a user
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/users/2" using HTTP DELETE
    Then the response code is 204
