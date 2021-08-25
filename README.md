# omnipay-pay360-hosted-cashier

**Redirect gateway driver for Pay360's Hosted Cashier service**

Omnipay implementation of Pay360's Hosted Cashier gateway.

See [Pay360's documentation](https://docs.pay360.com/cards/payments/) for more details.

## Installation

This driver is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "digitickets/omnipay-pay360-hosted-cashier": "^1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## What's Included

This driver allows you to redirect the user to a Pay360 Hosted Cashier page, after passing in customer details from your own forms and a redirect URL. 
Once the user has paid they will be redirected back to your redirect page. You can then confirm the payment through the driver. You may also pass in a notification URL (webhook) if you don't want to rely on a redirection.

It also supports refunds of partial and full amounts.

It only handles card payments.

## What's Not Included

This driver does not handle any of the other card management operations, such as subscriptions (repeat payments).

It does not support re-use of customer records.

## Basic Usage

For general Omnipay usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

### Required Parameters

You must pass the following parameters into the driver when calling `purchase()`, `refund()` or `completePurchase()`. You will be sent these by the Pay360 onboarding team.

```
installationId
apiUsername
apiPassword
```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug in this driver, please report it using the [GitHub issue tracker](https://github.com/digitickets/omnipay-pay360-hosted-cashier/issues),
or better yet, fork the library and submit a pull request.
