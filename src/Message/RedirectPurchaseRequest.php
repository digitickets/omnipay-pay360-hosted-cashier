<?php

namespace DigiTickets\OmnipayPay360HostedCashier\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use function sprintf;

class RedirectPurchaseRequest extends AbstractPay360Request
{
    // Endpoint at pay360 to create a hosted session, passing in customer details and transaction.
    // Returns a URL to redirect the customer to the hosted session at Pay360.
    protected $apiResourceString = '%s/hosted/rest/sessions/%s/payments';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('amount', 'card');

        $card = $this->getCard();

        return [
            'session' => $this->getSessionData(),
            'transaction' => $this->getTransactionData(),
            'customer' => $this->getCustomerData($card),
        ];
    }

    protected function getCustomerData(CreditCard $card): array
    {
        return [
            'registered' => false,
            'details' => [
                'name' => $card->getName(),
                'address' => [
                    'line1' => $card->getBillingAddress1(),
                    'line2' => $card->getBillingAddress2(),
                    'city' => $card->getBillingCity(),
                    'region' => $card->getBillingState(),
                    'postcode' => $card->getBillingPostcode(),
                    'countryCode' => $card->getBillingCountry(),
                ],
                'telephone' => $card->getBillingPhone(),
                'emailAddress' => $card->getEmail(),
            ],
        ];
    }

    /**
     * @throws InvalidRequestException
     */
    protected function getTransactionData(): array
    {
        return [
            'merchantReference' => $this->getTransactionId(),
            'money' => [
                'currency' => $this->getCurrency(),
                'amount' => [
                    'fixed' => $this->getAmount(),
                ],
            ],
        ];
    }

    protected function getSessionData(): array
    {
        $data = [
            'returnUrl' => [
                'url' => $this->getReturnUrl(),
            ],
        ];
        if ($this->getNotifyUrl()) {
            $data['transactionNotification'] = [
                'url' => $this->getNotifyUrl(),
            ];
        }

        return $data;
    }

    /**
     * @throws InvalidResponseException
     */
    public function sendData($data): RedirectPurchaseResponse
    {
        // post to the endpoint, getting the redirect URL back to Pay360
        $httpResponse = parent::sendData($data);

        return $this->response = new RedirectPurchaseResponse(
            $this,
            $httpResponse->json()
        );
    }

    public function getEndpoint(): string
    {
        return sprintf(
            $this->apiResourceString,
            parent::getEndpoint(),
            $this->getInstallationId()
        );
    }
}
