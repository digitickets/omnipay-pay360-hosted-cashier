<?php

namespace DigiTickets\OmnipayVerifoneCheckout\Test;

use DigiTickets\OmnipayPay360HostedCashier\Message\RedirectPurchaseRequest;
use DigiTickets\OmnipayPay360HostedCashier\Message\RedirectPurchaseResponse;
use Mockery;
use Omnipay\Tests\TestCase;

class RedirectPurchaseRequestTest extends TestCase
{
    public function testCreatingPurchaseRedirect()
    {
        $request = Mockery::mock(RedirectPurchaseRequest::class);

        $data = ['status' => 'SUCCESS', 'redirectUrl' => 'http://google.com'];

        $purchaseResponse = new RedirectPurchaseResponse($request, $data);

        $this->assertFalse($purchaseResponse->isSuccessful());
        $this->assertTrue($purchaseResponse->isRedirect());
        $this->assertEquals('http://google.com', $purchaseResponse->getRedirectUrl());
        $this->assertEquals('GET', $purchaseResponse->getRedirectMethod());
    }
}
