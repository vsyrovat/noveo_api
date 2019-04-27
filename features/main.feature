Feature: groups and users management
  User Stories:
  - I want to create a user who is included in a group
  - I want to check if this user exits and is active
  - I want to modify the list of users of a group

  Scenario: create a group
    Given group "Spaceship operators" does not exists
    When API-user sends POST request to "/groups/"
    """
    {"name":"Spaceship operators"}
    """
    Then group "Spaceship operators" should exists
    And the JSON node "success" should be true
    And response body should be equal to id of group "Spaceship operators"

  Scenario: error create group with same name
    Given group "Monkey moneymakers" exists
    When API-user sends POST request to "/groups/"
    """
    {"name": "Monkey moneymakers"}
    """
    Then the response status code should be 400
    And the JSON node "success" should be false

  Scenario: get list of the groups
    Given group "Captains" exists
    And group "Pirates" exists
    When API-user sends GET request to "/groups/"
    Then response should be standard JSON-success
    And the JSON node "success" should be true
    And the JSON node "data[0].name" should be equal to "Captains"
    And the JSON node "data[1].name" should be equal to "Pirates"
