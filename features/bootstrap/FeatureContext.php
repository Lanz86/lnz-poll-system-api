<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiYWQ0ODM4NGI2NWM1NTZhNzhjYzI5OTFkMjlkMWI1ZDgyMDAyYTAwOTI3MGE0MzliY2EwMmE5MmY5OGEwMDNmNjBlYmQ5OGNmOGEzZWNmMTIiLCJpYXQiOjE1OTIzODk1NTQsIm5iZiI6MTU5MjM4OTU1NCwiZXhwIjoxNjIzOTI1NTU0LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.HlCEQG5p2mRMwdeEkv67_JY5GC9LHTK6XI3jxi7VncYGVAgPl45fpcyusomWyaJkUWSqi4KhdRpJitmdSkCwDuxR8uqB2WNZm04MPn8wTQ_aBY4_FxY8BuPe6gZEHOLQD7F8UawxK8wKfdUgibrTTnsp4yRIf_6nRPhVoqiONXpz5zqAIcmBt789K7wkrIREOtJGHAtzTajB4cheMP-aVWfoI9U_tL8Ao-nwrByR8NygC0ilrzb-ePtlklABPWWNphgxhDHEiaGNGvDqEYqHsmuy1RthcOZecAS-u_UVaJMDZn-l_OjGRQG_CHSkLdBAtbGycV0m8PnqeLAOjfJUEa4I7rTMO_1D7HVblqPkA2BJ5xHS6vi2n1GTf2zDtQAZFDjAZdowPsY7RkUY9YBgsafxQ-9fO5K3zizrppg6cXwFZx1glH10rungR_IS0n4nlotSCHpwMEYez1o1BzYU5iBl0k2k1HDSWx3FQOERET4_iAuEyJ8ZMUIVOKWeFQkJY2XwL_JSwBut89ZSY2CCP_Osf8Ayw1d1LSY4bQ72L9nTGVnHnpmAkFCyx4hOx2b_APdUXiBD2T36zkQ5dIQk_l8Pw-bqoF6U3E0tbuULricmHEZNZF0bm4o4UPNnOt92c4PdpjAXiIz4nyH5hifTbjsnICXM53dDr-yw97Qc13E";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://127.0.0.1:8000' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }
}
