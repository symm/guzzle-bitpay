BitPay Client for PHP
=====================

[![Build Status](https://travis-ci.org/symm/guzzle-bitpay.png?branch=master)](https://travis-ci.org/symm/guzzle-bitpay)

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
