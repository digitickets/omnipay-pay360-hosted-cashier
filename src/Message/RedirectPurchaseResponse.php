<?php

namespace DigiTickets\OmnipayPay360HostedCashier\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class RedirectPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    const RESULT_SUCCESS = 'SUCCESS';

    /**
     * @throws InvalidResponseException
     */
    public function __construct(
        RequestInterface $request,
        array $jsonData
    ) {
        parent::__construct($request, $jsonData);
        if (!isset($this->data['status'])) {
            throw new InvalidResponseException('Pay 360 did not return a valid json string when requesting a hosted session');
        }
    }

    // returning false here forces the customer to be redirected (to Pay360)
    public function isSuccessful(): bool
    {
        return false;
    }

    // if Pay360 returns failure, we don't want to redirect to Pay360
    public function isRedirect(): bool
    {
        return isset($this->data['status'])
            && static::RESULT_SUCCESS === $this->data['status'];
    }

    /**
     * @return string|null
     */
    public function getTransactionReference()
    {
        return $this->data['sessionId'];
    }

    public function getTransactionId(): string
    {
        return $this->data->merchantreference;
    }

    /**
     * Information on any failure creating the hosted session
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->data['reasonMessage'];
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->data['reasonCode'];
    }


    /**
     * Url to redirect to the customer to pay360
     */
    public function getRedirectUrl(): string
    {
        return $this->data['redirectUrl'];
    }

    public function getRedirectMethod() : string
    {
        return 'GET';
    }

    public function getRedirectData() : array
    {
        return $this->data;
    }
}
