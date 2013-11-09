BitPay Client for PHP
=====================

![Build Status](https://api.travis-ci.org/symm/guzzle-bitpay.png)

Access the [BitPay API v0.3](https://bitpay.com/bitcoin-payment-gateway-api) in PHP using [Guzzle](http://guzzlephp.org).

[Official API Documentation](https://bitpay.com/downloads/bitpayApi.pdf)

Usage
-----

    use Symm\BitpayClient\BitpayClient;

    $config = array(
        'apiKey' => 'test'
    );
    $client = BitpayClient::factory($config);

    $response = $client->createInvoice(array('price' => 3, 'currency' => 'BTC'));
    $response = $client->getInvoice(array('id' => 3));

Available Actions
-----------------

    $api->createInvoice()
    $api->getInvoice()
