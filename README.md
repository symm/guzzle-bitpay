# BitPay Client for PHP

[![Build Status](https://travis-ci.org/symm/guzzle-bitpay.png?branch=master)](https://travis-ci.org/symm/guzzle-bitpay)
[![Code Coverage](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/badges/coverage.png?s=d9c3fdee868426cca2068918000dcc535f6fa62b)](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/badges/quality-score.png?s=5966642768365302617000fa075303b29858eb82)](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/)
[![Latest Stable Version](https://poser.pugx.org/symm/guzzle-bitpay/v/stable.png)](https://packagist.org/packages/symm/guzzle-bitpay)
[![Total Downloads](https://poser.pugx.org/symm/guzzle-bitpay/downloads.png)](https://packagist.org/packages/symm/guzzle-bitpay)
[![Latest Unstable Version](https://poser.pugx.org/symm/guzzle-bitpay/v/unstable.png)](https://packagist.org/packages/symm/guzzle-bitpay)
[![License](https://poser.pugx.org/symm/guzzle-bitpay/license.png)](https://packagist.org/packages/symm/guzzle-bitpay)

Add BitPay payment processing support to your PHP application using the [BitPay API](https://bitpay.com/bitcoin-payment-gateway-api) and [Guzzle](https://guzzle.readthedocs.org/)

## Installation

Require the library in your [composer.json](https://getcomposer.org/) file:

``` json
{
    "require": {
        "symm/guzzle-bitpay": "~1.0"
    }
}
```

## Usage

### Create and configure a client

``` php
use Symm\BitpayClient\BitpayClient;
$client = BitpayClient::factory(
    array(
        'apiKey' => 'YOUR_API_KEY_HERE',
    )
);
```

### Create a new invoice

``` php
$invoice = $client->createInvoice(
    array(
        'price'    => 0.0001,
        'currency' => 'BTC',
    )
);
echo $invoice->getUrl() . PHP_EOL;
```

### Receive an existing invoice

``` php
$invoice = $client->getInvoice(
    array(
        'id' => 'XXXXXXXXXXXXXXXXXXXXXX'
    )
);
echo $invoice->getStatus() . PHP_EOL;
```

### Verify BitPay Notification

``` php
$notificationString = file_get_contents("php://input");
$invoice = $client->verifyNotification($notificationString);
```

### Get exchange rates

``` php
$currencyCollection = $client->getRates();
foreach ($currencyCollection as $currency) {
    /** @var \Symm\BitpayClient\Model\Currency $currency */
    echo $currency->getCode() . ': ' . $currency->getRate();
}
```

### Localise Invoice page

``` php
use Symm\BitpayClient\Localisation\Language;
echo $invoice->getUrl(Language::SPANISH)
```

## Resources

[Guzzle Documentation](https://guzzle.readthedocs.org/en/latest/docs.html)

[Official API Documentation](https://bitpay.com/downloads/bitpayApi.pdf)

## Copyright and license

Code copyright Gareth Jones and released under [the MIT license](LICENSE).

If you enjoy this library, please consider making a donation: [1CMaGyGXWsFbmQ8HmuzJ6qCe1NQ9ypcS2E](bitcoin:1CMaGyGXWsFbmQ8HmuzJ6qCe1NQ9ypcS2E?message=guzzle-bitpay-donation)
