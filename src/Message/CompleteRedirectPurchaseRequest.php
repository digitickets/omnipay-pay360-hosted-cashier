<?php

namespace DigiTickets\OmnipayPay360HostedCashier\Message;

/**
 * Gets the transaction status from Pay360
 */
class CompleteRedirectPurchaseRequest extends AbstractPay360Request
{
    protected $apiResourceString = '%s/hosted/rest/sessions/%s/%s/status';

    public function getData(): array
    {
        return $this->httpRequest->query->all();
    }

    /**
     * @param mixed $data
     *
     * @return CompleteRedirectPurchaseResponse
     */
    public function sendData($data): CompleteRedirectPurchaseResponse
    {
        if ($this->getTestMode()) {
            //disable ssl verification if in test mode
            $this->httpClient->setConfig(['verify' => false]);
        }

        $httpResponse = $this->httpClient->get(
            $this->getEndpoint(),
            $this->getHeaders()
        )->send();

        return $this->response = new CompleteRedirectPurchaseResponse(
            $this,
            $httpResponse->json()
        );
    }

    public function getEndpoint(): string
    {
        return sprintf($this->apiResourceString, parent::getEndpoint(),
            $this->getInstallationId(),
            $this->httpRequest->query->get('sessionId')
        );
    }
}
