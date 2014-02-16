<?php

namespace Symm\BitpayClient\Tests;

use Guzzle\Tests\GuzzleTestCase;

use Symm\BitpayClient\BitpayClient;

class BitpayClientTest extends GuzzleTestCase
{
    protected $config = array(
        'apiKey' => 'TESTAPIKEY'
    );

    protected function getClient()
    {
        $client = BitpayClient::factory($this->config);

        return $client;
    }

    public function testBuilderCreatesClient()
    {
        $client = $this->getClient();
        $this->assertInstanceOf('\Symm\BitpayClient\BitpayClient', $client);
    }

    /**
     * @expectedException \Guzzle\Common\Exception\InvalidArgumentException
     */
    public function testClientRequiresApiKey()
    {
        $client = BitpayClient::factory(array());
    }

    public function testClientConfiguresBaseUrl()
    {
        $client  = $this->getClient();
        $baseUrl = $client->getConfig('base_url');

        $this->assertEquals('https://bitpay.com/api', $baseUrl);
    }

    public function testClientConfiguresBasicAuthWithTheApiKey()
    {
        $client = $this->getClient();
        $requestOptions = $client->getConfig('request.options');

        $this->assertEquals($this->config['apiKey'], $requestOptions['auth'][0]);
        $this->assertEquals("", $requestOptions['auth'][1]);
        $this->assertEquals("Basic", $requestOptions['auth'][2]);
    }

    public function testClientSetsTheUserAgent()
    {
        $client = $this->getClient();
        $userAgent = $client->getCommand(
            'getInvoice',
            array(
                'id' => '1234'
            )
        )
            ->prepare()
            ->getHeader('User-Agent')
            ->__toString();

        $this->assertEquals('Guzzle BitpayClient - https://github.com/symm/guzzle-bitpay', $userAgent);
    }

    public function testCreateInvoiceReturnsInvoiceModel()
    {
        $client = $this->getClient();
        $this->setMockResponse($client, 'createInvoice');

        $invoice = $client->createInvoice(array(
            'price'    => 0.0001,
            'currency' => 'BTC',
            'posData'  => 'Test Pos data',
            'itemDesc' => 'Test transaction'
        ));

        $this->assertInstanceOf('\Symm\BitpayClient\Model\Invoice', $invoice);
    }

    public function testGetInvoiceReturnsInvoiceModel()
    {
        $client = $this->getClient();
        $this->setMockResponse($client, 'createInvoice');
        $invoice = $client->getInvoice(array(
            'id' => 'XXXXXXXXXXXXXXXXXXXXXX'
        ));

        $this->assertInstanceOf('\Symm\BitpayClient\Model\Invoice', $invoice);
    }

    public function getRatesReturnsCurrencyCollection()
    {
        $client = $this->getClient();
        $this->setMockResponse($client, 'getRates');
        $rates = $client->getRates();

        $this->assertInstanceOf('\Symm\BitpayClient\Model\CurrencyCollection', $rates);
    }

    /**
     * @expectedException \Symm\BitpayClient\Exceptions\InvalidJsonResponseException
     */
    public function testVerifyNotificationBadJson()
    {
        $client = $this->getClient();

        $client->verifyNotification('bad json');
    }

    /**
     * @expectedException \Symm\BitpayClient\Exceptions\InvalidJsonResponseException
     */
    public function testVerifyNotificationMissingPosdata()
    {
        $client = $this->getClient();

        $response = json_encode(
            array(
                'test' => true
            )
        );

        $client->verifyNotification($response);
    }

    /**
     * @expectedException \Symm\BitpayClient\Exceptions\InvalidJsonResponseException
     */
    public function testVerifyNotificationMissingHashData()
    {
        $client = $this->getClient();
        $response = json_encode(
            array(
                'posData' => ''
            )
        );

        $client->verifyNotification($response);

    }

    /**
     * @expectedException \Symm\BitpayClient\Exceptions\InvalidJsonResponseException
     */
    public function testVerifyNotificationEmptyPosData()
    {

        $client = $this->getClient();
        $response = json_encode(
            array(
            'posData' => '',
            )
        );

        $client->verifyNotification($response);
    }

    /**
     * @expectedException \Symm\BitpayClient\Exceptions\AuthenticationFailedException
     */
    public function testVerifyNoticationBadHash()
    {
        $client = $this->getClient();

        $response = json_encode(
            array(
            'posData' => 'order 1234',
            'hash'    => 'badhash',
            )
        );

        $client->verifyNotification($response);
    }
}
