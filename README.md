# BitPay Client for PHP

[![Build Status](https://travis-ci.org/symm/guzzle-bitpay.png?branch=master)](https://travis-ci.org/symm/guzzle-bitpay)
[![Code Coverage](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/badges/coverage.png?s=d9c3fdee868426cca2068918000dcc535f6fa62b)](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/badges/quality-score.png?s=5966642768365302617000fa075303b29858eb82)](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/)

Add BitPay payment processing support to your PHP application using the [BitPay API](https://bitpay.com/bitcoin-payment-gateway-api) and [Guzzle](https://guzzle.readthedocs.org/)

## Installation

Require the library in your [composer.json](https://getcomposer.org/) file:

``` json
{
    "require": {
        "symm/guzzle-bitpay": "dev-master"
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

### Get exchange rates

``` php
$currencyCollection = $client->getRates();
foreach ($currencyCollection as $currency) {
    /** @var \Symm\BitpayClient\Model\Currency $currency */
    echo $currency->getCode() . ': ' . $currency->getRate();
}
```

## Resources

[Guzzle Documentation](https://guzzle.readthedocs.org/en/latest/docs.html)

[Official API Documentation](https://bitpay.com/downloads/bitpayApi.pdf)

## Copyright and license

Code copyright Gareth Jones and released under [the MIT license](LICENSE).

If you enjoy this library, please consider making a donation: [1CMaGyGXWsFbmQ8HmuzJ6qCe1NQ9ypcS2E](bitcoin:1CMaGyGXWsFbmQ8HmuzJ6qCe1NQ9ypcS2E?message=guzzle-bitpay-donation)
