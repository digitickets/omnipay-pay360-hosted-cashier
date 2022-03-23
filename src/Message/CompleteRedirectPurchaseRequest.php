<?php

namespace DigiTickets\OmnipayPay360HostedCashier\Message;

use Omnipay\Common\Exception\InvalidRequestException;

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

    /**
     * @return string
     * @throws InvalidRequestException
     */
    public function getEndpoint(): string
    {
        $sessionID = $this->httpRequest->query->get('sessionId');
        if (!$sessionID) {
            // If this is from a webhook, we get the full transaction details in the post body
            $json = json_decode($this->httpRequest->getContent(), true);
            if ($json && !empty($json["sessionId"])) {
                $sessionID = $json["sessionId"];
            }
        }
        if (!$sessionID) {
            throw new InvalidRequestException("Missing sessionId in query string request/callback from Pay360");
        }

        return sprintf(
            $this->apiResourceString,
            parent::getEndpoint(),
            $this->getInstallationId(),
            $sessionID
        );
    }
}
