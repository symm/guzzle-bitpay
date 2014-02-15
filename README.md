BitPay Client for PHP
=====================

[![Build Status](https://travis-ci.org/symm/guzzle-bitpay.png?branch=master)](https://travis-ci.org/symm/guzzle-bitpay)
[![Code Coverage](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/badges/coverage.png?s=d9c3fdee868426cca2068918000dcc535f6fa62b)](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/badges/quality-score.png?s=5966642768365302617000fa075303b29858eb82)](https://scrutinizer-ci.com/g/symm/guzzle-bitpay/)

Access the [BitPay API v0.3](https://bitpay.com/bitcoin-payment-gateway-api) in PHP using [Guzzle](http://guzzlephp.org).

[Official API Documentation](https://bitpay.com/downloads/bitpayApi.pdf)

Usage
-----
    use Symm\BitpayClient\BitpayClient;

    $config = array(
        'apiKey' => 'test'
    );
    $client = BitpayClient::factory($config);

    $invoice  = $client->createInvoice(array('price' => 3, 'currency' => 'BTC'));
    // Will return a Symm\BitpayClient\Model\Invoice

    $response = $client->getInvoice(array('id' => $invoice->getId()));
    // Will return a Symm\BitpayClient\Model\Invoice

    $currencyCollection = $client->getRates();
    // Returns a CurrencyCollection
    foreach ($currencyCollection as $currency) {
        echo $currency->getName() . ': ' . $currency->getRate() . PHP_EOL;
    }

Available Actions
-----------------
    $api->createInvoice()
    $api->getInvoice()
    $api->getRates()
