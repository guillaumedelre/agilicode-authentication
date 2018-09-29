Feature: User
  In order to use the application
  I need to be able to list users and get detail trough the API.

  Background:
    Given the following fixtures are loaded:
    """
    api/crud-user.yaml
    """
    And the "X-APP-ENV" request header is "test"
    Given the jwt for "admin"
    And I save it into "XBearer"

  Scenario: read a user collection
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/users" using HTTP GET
    Then the response code is 200
    And the JSON node "$.hydra:totalItems" should be equal to 2
    And the JSON node "$.hydra:member" should have 2 elements

    And the JSON node "$.hydra:member.0.@type" should be equal to "User"
    And the JSON node "$.hydra:member.0.@id" should be equal to "/api/users/1"
    And the JSON node "$.hydra:member.0.username" should be equal to "admin"
    And the JSON node "$.hydra:member.0.password" should not be an empty string
    And the JSON node "$.hydra:member.0.roles" should have 1 elements
    And the JSON node "$.hydra:member.0.roles.0" should be equal to "administrator"
    And the JSON node "$.hydra:member.0.userRoles" should have 1 elements
    And the JSON node "$.hydra:member.0.userRoles.0" should be equal to "/api/user_roles/1"
    And the JSON node "$.hydra:member.0.permissions" should have 0 elements

    And the JSON node "$.hydra:member.1.@type" should be equal to "User"
    And the JSON node "$.hydra:member.1.@id" should be equal to "/api/users/2"
    And the JSON node "$.hydra:member.1.username" should be equal to "johndoe"
    And the JSON node "$.hydra:member.1.password" should not be an empty string
    And the JSON node "$.hydra:member.1.roles" should have 1 elements
    And the JSON node "$.hydra:member.1.roles.0" should be equal to "user"
    And the JSON node "$.hydra:member.1.userRoles" should have 1 elements
    And the JSON node "$.hydra:member.1.userRoles.0" should be equal to "/api/user_roles/2"
    And the JSON node "$.hydra:member.1.permissions" should have 0 elements

  Scenario: create a user item
    Given the "Authorization" request header is "Bearer <<XBearer>>"
    And the "Content-Type" request header is "application/json"
    When I request "/api/users/1" using HTTP GET
    Then the response code is 200
    And the JSON node "$.@type" should be equal to "User"
    And the JSON node "$.@id" should be equal to "/api/users/1"
    And the JSON node "$.username" should be equal to "admin"
    And the JSON node "$.password" should not be an empty string
    And the JSON node "$.roles" should have 1 elements
    And the JSON node "$.roles.0" should be equal to "administrator"
    And the JSON node "$.userRoles" should have 1 elements
    And the JSON node "$.userRoles.0" should be equal to "/api/user_roles/1"
    And the JSON node "$.permissions" should have 0 elements
