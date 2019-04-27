<?php declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\MinkContext;
use PHPUnit\Framework\Assert;

class HttpContext implements Context
{
    /** @var RestContext */
    private $restContext;
    /** @var GroupsAndUsersContext */
    private $groupsAndUsersContext;
    /** @var JsonContext */
    private $jsonContext;
    /** @var MinkContext */
    private $minkContext;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        if (!$environment instanceof InitializedContextEnvironment) {
            throw new \LogicException('FeatureContext cannot be correctly initialized without access to subcontexts.');
        }

        $this->restContext = $environment->getContext(RestContext::class);
        $this->groupsAndUsersContext = $environment->getContext(GroupsAndUsersContext::class);
        $this->jsonContext = $environment->getContext(JsonContext::class);
        $this->minkContext = $environment->getContext(MinkContext::class);
    }

    /** @When API-user sends :method request to :url */
    public function apiUserSendsRequest(string $method, string $url, PyStringNode $body = null, $files = [])
    {
        $this->restContext->iAddHeaderEqualTo('Content-Type', 'application/json');
        $this->restContext->iAddHeaderEqualTo('Accept', 'application/json');
        $this->restContext->iSendARequestTo($method, $url, $body, $files);
    }

    /** @Then response body should be equal to id of group :groupName */
    public function responseShouldBeEqualToGroupId(string $groupName)
    {
        $group = $this->groupsAndUsersContext->readGroupByName($groupName);
        $this->restContext->theResponseShouldNotBeEmpty();
        $this->restContext->theResponseShouldBeInJson();
        $this->jsonContext->theJsonNodeShouldBeEqualToValue('data', ['id' => $group->getId()]);
    }

    /** @Then response should be standard JSON-success */
    public function responseShouldBeStandardJsonSuccess()
    {
        $this->restContext->theResponseShouldNotBeEmpty();
        Assert::assertContains($this->minkContext->getSession()->getStatusCode(), [200, 201]);
        $this->restContext->theResponseShouldBeInJson();
    }
}