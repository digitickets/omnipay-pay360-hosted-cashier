<?php

namespace DigiTickets\OmnipayPay360HostedCashier;

use DigiTickets\OmnipayPay360HostedCashier\Message\CompleteRedirectPurchaseRequest;
use DigiTickets\OmnipayPay360HostedCashier\Message\RedirectPurchaseRequest;
use DigiTickets\OmnipayPay360HostedCashier\Message\RefundRequest;
use DigiTickets\OmnipayPay360HostedCashier\Traits\GatewayParamsTrait;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;

/**
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options =[])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array$options = [])
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options =[])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options =[])
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options =[])
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options =[])
 */
class Gateway extends AbstractGateway
{
    use GatewayParamsTrait;

    public function getName(): string
    {
        return 'Pay360HostedCashier';
    }

    public function getDefaultParameters(): array
    {
        return [
            'installationId' => '',
            'apiUsername' => '',
            'apiPassword' => '',
            'testMode' => false,
        ];
    }

    /**
     * Call when Pay360 sends a callback request to the merchant system (from notifyUrl param),
     * and/or when Pay360 redirects back to the merchant site (from redirectUrl param).
     * This validates that the payment was successful or was denied.
     * @return AbstractRequest|CompleteRedirectPurchaseRequest
     */
    public function completePurchase(array $options = []): RequestInterface
    {
        $options = array_merge($this->getParameters(), $options);

        return $this->createRequest(
            CompleteRedirectPurchaseRequest::class,
            $options
        );
    }

    /**
     * @return AbstractRequest|RedirectPurchaseRequest - provides a url to redirect to
     */
    public function purchase(array $options = []): RequestInterface
    {
        $options = array_merge($this->getParameters(), $options);

        return $this->createRequest(
            RedirectPurchaseRequest::class,
            $options
        );
    }

    /**
     * @param array $options
     * @return AbstractRequest|RefundRequest
     */
    public function refund(array $options = []): RequestInterface
    {
        return $this->createRequest(RefundRequest::class, $options);
    }
}
