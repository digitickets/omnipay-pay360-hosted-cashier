<?php

namespace DigiTickets\OmnipayPay360HostedCashier\Message;

use DigiTickets\OmnipayPay360HostedCashier\Traits\GatewayParamsTrait;
use Omnipay\Common\Message\AbstractRequest;

abstract class AbstractPay360Request extends AbstractRequest
{
    use GatewayParamsTrait;

    protected $liveEndpoint = 'https://api.pay360.com';
    protected $testEndpoint = 'https://api.mite.pay360.com';

    // Placeholder for the resource endpoint of the particular request
    protected $apiResourceString = '';

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Basic '.base64_encode(
                    sprintf('%s:%s', $this->getApiUsername(),
                        $this->getApiPassword())),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * @param array $data
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function sendData($data)
    {
        if ($this->getTestMode()) {
            //disable ssl verification if in test mode
            $this->httpClient->setConfig(['verify' => false]);
        }

        $jsonData = json_encode($data);

        return $this->httpClient->post(
            $this->getEndpoint(),
            $this->getHeaders(),
            $jsonData
        )->send();
    }
}
