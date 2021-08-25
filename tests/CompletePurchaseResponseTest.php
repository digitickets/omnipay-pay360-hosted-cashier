<?php

namespace DigiTickets\OmnipayVerifoneCheckout\Test;

use DigiTickets\OmnipayPay360HostedCashier\Message\CompleteRedirectPurchaseRequest;
use DigiTickets\OmnipayPay360HostedCashier\Message\CompleteRedirectPurchaseResponse;
use Mockery;
use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    /**
     * @dataProvider creationProvider
     */
    public function testCreatingCompletePurchaseResponse(
        array $data,
        bool $expectedSuccess,
        string $expectedMessage
    ) {
        $request = Mockery::mock(CompleteRedirectPurchaseRequest::class);

        $response = new CompleteRedirectPurchaseResponse($request, $data);

        $this->assertEquals($expectedSuccess, $response->isSuccessful());
        $this->assertEquals($expectedMessage, $response->getMessage());
    }

    /**
     * @return array
     */
    public function creationProvider()
    {
        return [
            'success' => [
                ['status' => 'SUCCESS', 'hostedSessionStatus'=>['transactionState'=>['transactionState'=>'SUCCESS']]],
                true,
                'SUCCESS',
            ],
            'declined' => [
                ['status' => 'FAILED', 'reasonCode'=>'aaa', 'reasonMessage'=>'Reason'],
                false,
                'Reason',
            ],
            'error' => [
                ['status' => 'SUCCESS', 'hostedSessionStatus'=>['transactionState'=>['transactionState'=>'FAILED']]],
                false,
                'FAILED',
            ],
        ];
    }
}
