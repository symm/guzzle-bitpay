BitPay Client for PHP
=====================

[![Build Status](https://travis-ci.org/symm/guzzle-bitpay.png?branch=master)](https://travis-ci.org/symm/guzzle-bitpay)
[![Code Coverage](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/badges/coverage.png?s=d9c3fdee868426cca2068918000dcc535f6fa62b)](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/badges/quality-score.png?s=5966642768365302617000fa075303b29858eb82)](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/)

Access the [BitPay API v0.3](https://bitpay.com/bitcoin-payment-gateway-api) in PHP using [Guzzle](http://guzzlephp.org).

[Official API Documentation](https://bitpay.com/downloads/bitpayApi.pdf)

Usage
-----

```php
<?php

require_once('vendor/autoload.php');

use Symm\BitpayClient\BitpayClient;

$client = BitpayClient::factory(
    array(
        'apiKey' => 'YOUR_API_KEY_HERE',
    )
);

// Create a new Invoice
$newInvoice = $client->createInvoice(
    array(
        'price'    => 0.0001,
        'currency' => 'BTC',
    )
);
echo 'Invoice ('. $newInvoice->getId() . ') can be paid at: ' . $newInvoice->getUrl() . PHP_EOL;

// Check the status of an existing invoice
$existingInvoice = $client->getInvoice(
    array(
        'id' => $newInvoice->getId()
    )
);
echo 'Invoice ' . $existingInvoice->getId() . ' has status: ' . $existingInvoice->getStatus() . PHP_EOL;

// Get the current exchange Rates
$currencyCollection = $client->getRates();
/** @var \Symm\BitpayClient\Model\Currency $currency */
foreach ($currencyCollection as $currency) {
    echo $currency->getCode() . ': ' . $currency->getRate() . '(' .$currency->getName() .')'. PHP_EOL;
}
```

Available Actions
-----------------
    $api->createInvoice()
    $api->getInvoice()
    $api->getRates()
