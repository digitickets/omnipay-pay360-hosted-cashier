<?php

namespace DigiTickets\OmnipayPay360HostedCashier\Test;

use DigiTickets\OmnipayPay360HostedCashier\Message\RefundRequest;
use DigiTickets\OmnipayPay360HostedCashier\Message\RefundResponse;
use Mockery;
use Omnipay\Tests\TestCase;

class RefundResponseTest extends TestCase
{
    /**
     * @dataProvider creationProvider
     */
    public function testCreatingRefund(
        array $data,
        bool $expectedSuccess,
        string $expectedMessage,
        string $transactionRef
    ) {
        $request = Mockery::mock(RefundRequest::class);

        $response = new RefundResponse($request, $data);

        $this->assertEquals($expectedSuccess, $response->isSuccessful());
        $this->assertEquals($expectedMessage, $response->getMessage());
        $this->assertEquals($transactionRef, $response->getTransactionReference());
    }

    /**
     * @return array
     */
    public function creationProvider()
    {
        return [
            'success' => [
                ['outcome' => ['status' => 'SUCCESS'], 'transaction' => ['transactionId' => '123']],
                true,
                'SUCCESS',
                '123',
            ],
            'failed' => [
                [
                    'outcome' => ['status' => 'FAILED', 'reasonMessage' => 'failed reason'],
                    'transaction' => ['transactionId' => '123'],
                ],
                false,
                'FAILED - failed reason',
                '123',
            ],
        ];
    }
}
