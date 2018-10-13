<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 24/09/18
 * Time: 20:52
 */

namespace App\Behat\Context;

use Assert\Assertion;
use Assert\AssertionFailedException as AssertionFailure;
use Imbo\BehatApiExtension\Context\ApiContext;
use Imbo\BehatApiExtension\Exception\AssertionFailedException;
use Peekmo\JsonPath\JsonStore;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ExtendedApiContext extends ApiContext
{
    /**
     * @var JsonEncoder
     */
    private $jsonEncoder;

    /**
     * ExtendedApiContext constructor.
     *
     * @param JsonEncoder $jsonEncoder
     */
    public function __construct(JsonEncoder $jsonEncoder)
    {
        $this->jsonEncoder = $jsonEncoder;
    }

    /**
     * @Then I clear form parameters
     */
    public function iClearFormParameters()
    {
        $this->requestOptions = [];
    }

    /**
     * @Given the :headerName request header
     */
    public function theRequestHeader($headerName)
    {
        $this->requireResponse();
        $headerData = $this->response->getHeader($headerName);

        return reset($headerData);
    }

    /**
     * @Given the JSON node :node
     */
    public function theJsonNode($node)
    {
        try {
            return $this->evaluateJsonResponse($node);
        } catch (\Exception $e) {
            throw new AssertionFailedException($e->getMessage());
        }
    }

    /**
     * @When print last response body
     */
    public function printLastResponseBody()
    {
        echo $this->getLastResponseContents();
    }

    /**
     * @When print last response headers
     */
    public function printLastResponseHeaders()
    {
        $this->requireResponse();

        echo $this->outputHeaders($this->response->getHeaders());
    }

    /**
     * @When print last request headers
     */
    public function printLastRequestHeaders()
    {
        echo $this->outputHeaders($this->request->getHeaders());
    }

    /**
     * @param array $headers
     *
     * @return string
     */
    private function outputHeaders(array $headers = []): string
    {
        $return = '';
        foreach ($headers as $headerName => $headerData) {
            $headerData = $this->response->getHeader($headerName);
            $return .= sprintf("%s:\n\t%s\n", $headerName, implode("\n", $headerData));
        }

        return $return;

    }

    /**
     * @Then the JSON node :node should not be an empty string
     */
    public function theJsonNodeShouldNotBeAnEmptyString($node)
    {
        try {
            Assertion::notEmpty(
                $this->evaluateJsonResponse($node),
                sprintf('Expected JSON node %s, should not be empty.', $node)
            );
        } catch (AssertionFailure $e) {
            throw new AssertionFailedException($e->getMessage());
        }
    }

    /**
     * @Then the JSON node :node should be greater than :max
     */
    public function theJsonNodeShouldBeGreaterThan($node, $max)
    {
        try {
            Assertion::greaterThan(
                $this->evaluateJsonResponse($node),
                $max
            );
        } catch (AssertionFailure $e) {
            throw new AssertionFailedException($e->getMessage());
        }
    }

    /**
     * @Then the JSON node :node should have :total elements
     */
    public function theJsonNodeShouldHaveElements($node, $total)
    {
        try {
            Assertion::eq(
                count($this->evaluateJsonResponse($node)),
                $total
            );
        } catch (AssertionFailure $e) {
            throw new AssertionFailedException($e->getMessage());
        }
    }

    /**
     * @Then the JSON node :node should be equal to :expected
     */
    public function theJsonNodeShouldBeEqualTo($node, $expected)
    {
        try {
            Assertion::eq(
                $this->evaluateJsonResponse($node),
                $expected
            );
        } catch (AssertionFailure $e) {
            throw new AssertionFailedException($e->getMessage());
        }
    }

    /**
     * @Then the JSON node :node should be true
     */
    public function theJsonNodeShouldBeTrue($node)
    {
        try {
            Assertion::true(
                $this->evaluateJsonResponse($node)
            );
        } catch (AssertionFailure $e) {
            throw new AssertionFailedException($e->getMessage());
        }
    }
    /**
     * @Then the JSON node :node should be false
     */
    public function theJsonNodeShouldBeFalse($node)
    {
        try {
            Assertion::false(
                $this->evaluateJsonResponse($node)
            );
        } catch (AssertionFailure $e) {
            throw new AssertionFailedException($e->getMessage());
        }
    }

    /**
     * @param string $node
     *
     * @return mixed
     */
    private function evaluateJsonResponse(string $node)
    {
        $this->requireResponse();
        $store = new JsonStore($this->getLastResponseContents());

        return $store->get($node, true)[0] ?? null;
    }

    /**
     * @return string
     */
    private function getLastResponseContents(): string
    {
        echo "\nMINIFY\n";
        echo $this->response->getBody()->getContents();
        echo "\nPRETTY\n";
        $this->response->getBody()->rewind();
        $store = new JsonStore($this->response->getBody()->getContents());

        return json_encode($store->toArray(), JSON_PRETTY_PRINT);
    }
}