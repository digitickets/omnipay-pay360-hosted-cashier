<?php

namespace DigiTickets\OmnipayPay360HostedCashier\Message;

use Omnipay\Common\Message\AbstractResponse;

class RefundResponse extends AbstractResponse
{
    const SUCCESS_STATUS = 'SUCCESS';

    public function isSuccessful(): bool
    {
        return isset($this->data['outcome']) && !empty($this->data['outcome']['status']) && $this->data['outcome']['status'] === self::SUCCESS_STATUS;
    }

    public function getTransactionReference(): string
    {
        return $this->data['transaction']['transactionId'] ?? '';
    }

    public function getMessage(): string
    {
        return implode(
            ' - ',
            array_filter(
                [
                    $this->data['outcome']['status'] ?? '',
                    $this->data['outcome']['reasonMessage'] ?? '',
                ]
            )
        );
    }

    public function getCode(): string
    {
        return $this->data['outcome']['reasonCode'] ?? '';
    }
}
