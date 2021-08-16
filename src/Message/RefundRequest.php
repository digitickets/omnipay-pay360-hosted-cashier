<?php

namespace DigiTickets\OmnipayPay360HostedCashier\Message;

class RefundRequest extends AbstractPay360Request
{
    // Endpoint at Pay360 to post transaction details to refund
    protected $apiResourceString = '%s/acceptor/rest/transactions/%s/%s/refund';

    public function getData(): array
    {
        return [
            'transaction' => [
                'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
            ],
        ];
    }

    public function sendData($data) : RefundResponse
    {
        $httpResponse = parent::sendData($data);

        return $this->response = new RefundResponse(
            $this,
            $httpResponse->json()
        );
    }

    public function getEndpoint(): string
    {
        $endpoint = sprintf($this->apiResourceString, parent::getEndpoint(),
            $this->getInstallationId(),
            $this->getTransactionReference()
        );

        return $endpoint;
    }
}
