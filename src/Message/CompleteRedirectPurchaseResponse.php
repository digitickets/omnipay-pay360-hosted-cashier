<?php

namespace DigiTickets\OmnipayPay360HostedCashier\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompleteRedirectPurchaseResponse extends AbstractResponse
{
    const RESULT_SUCCESS = 'SUCCESS';

    public function isRedirect(): bool
    {
        return false;
    }

    public function isSuccessful(): bool
    {
        return isset($this->data['hostedSessionStatus']['transactionState']['transactionState']) &&
            static::RESULT_SUCCESS === $this->data['hostedSessionStatus']['transactionState']['transactionState'];
    }

    /**
     * TransactionState can be one of the following:
     * NOT_SUBMITTED – transaction request has not yet been submitted by customer
     * PROCESSING – transaction is being processed
     * PENDING – transaction is suspended, typically for 3-D Secure
     * SUCCESS – transaction was successful
     * FAILED – transaction failed
     * EXPIRED – transaction was abandoned
     * CANCELLED – transaction was cancelled
     * VOIDED – transaction was voided or reversed
     */
    public function getMessage(): string
    {
        return $this->data['reasonMessage'] ?? // Reason why session could not be retrieved
            $this->data['hostedSessionStatus']['transactionState']['transactionState']
            ?? '';
    }

    public function getCode(): string
    {
        return $this->data['reasonCode'] ??
            $this->data['hostedSessionStatus']['transactionState']['transactionState'] ??
            '';
    }

    public function getTransactionReference(): string
    {
        return $this->data['hostedSessionStatus']['transactionState']['id'] ?? '';
    }
}
