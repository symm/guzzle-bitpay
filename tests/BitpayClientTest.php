<?php

namespace Symm\BitpayClient\Tests;

use Guzzle\Tests\GuzzleTestCase;

use Symm\BitpayClient\BitpayClient;

class BitpayClientTest extends GuzzleTestCase
{
    private $apiKey = 'TESTAPIKEY';

    private function getClient()
    {
        $client = BitpayClient::createClient($this->apiKey);

        return $client;
    }

    public function testFactoryMethodReturnsAClient()
    {
        $client = BitpayClient::factory(
            array(
                'apiKey' => $this->apiKey
            )
        );

        $this->assertInstanceOf('\Symm\BitpayClient\BitpayClient', $client);
    }

    public function testCreateTestClientMethodReturnsAClient()
    {
        $client = BitpayClient::createTestClient($this->apiKey);

        $this->assertInstanceOf('\Symm\BitpayClient\BitpayClient', $client);
    }

    public function testApiKeyIsARequiredArguement()
    {
        $this->setExpectedException('\Guzzle\Common\Exception\InvalidArgumentException');
        BitpayClient::factory(array());
    }

    public function testClientUsesTheLiveHost()
    {
        $client  = $this->getClient();
        $baseUrl = $client->getConfig('base_url');

        $this->assertEquals('https://bitpay.com/api', $baseUrl);
    }

    public function testTestClientUsesTheTestHost()
    {
        $client  = BitpayClient::createTestClient($this->apiKey);
        $baseUrl = $client->getConfig('base_url');

        $this->assertEquals('https://test.bitpay.com/api', $baseUrl);
    }

    public function testClientUsesBasicAuthenticationWithTheApiKeyAsTheUsernameAndABlankPassword()
    {
        $client         = $this->getClient();
        $requestOptions = $client->getConfig('request.options');

        $this->assertEquals($this->apiKey, $requestOptions['auth'][0]);
        $this->assertEquals("", $requestOptions['auth'][1]);
        $this->assertEquals("Basic", $requestOptions['auth'][2]);
    }

    public function testClientHasBitPayClientInTheUserAgent()
    {
        $client    = $this->getClient();
        $userAgent = $client->getCommand(
            'getInvoice',
            array(
                'id' => '1234'
            )
        )
            ->prepare()
            ->getHeader('User-Agent')
            ->__toString();

        $this->assertEquals('BitpayClient - https://github.com/symm/guzzle-bitpay', $userAgent);
    }

    public function testCreateInvoiceShouldReturnAnInvoiceModel()
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

    public function testGetInvoiceShouldReturnAnInvoiceModel()
    {
        $client = $this->getClient();
        $this->setMockResponse($client, 'createInvoice');
        $invoice = $client->getInvoice(array(
            'id' => 'XXXXXXXXXXXXXXXXXXXXXX'
        ));

        $this->assertInstanceOf('\Symm\BitpayClient\Model\Invoice', $invoice);
    }

    public function testgetRatesShouldReturnACurrencyCollection()
    {
        $client = $this->getClient();
        $this->setMockResponse($client, 'getRates');
        $rates = $client->getRates();

        $this->assertInstanceOf('\Symm\BitpayClient\Model\CurrencyCollection', $rates);
    }

    public function testExceptionShouldBeThrownIfTheCallbackNotifcationIsBlank()
    {
        $this->setExpectedException('\Symm\BitpayClient\Exception\CallbackJsonMissingException');

        $client = $this->getClient();
        $client->verifyNotification('');
    }

    public function testExceptionShouldBeThrownIfTheCallbackNotificationIsNotValidJson()
    {
        $this->setExpectedException('\Symm\BitpayClient\Exception\CallbackInvalidJsonException');

        $client = $this->getClient();
        $client->verifyNotification("23kwreGSFDG£%");
    }

    public function testExceptionShouldBeThrownIfTheCallbackNotificationIsMissingThePosData()
    {
        $this->setExpectedException('\Symm\BitpayClient\Exception\CallbackPosDataMissingException');

        $client   = $this->getClient();
        $response = json_encode(
            array(
                'id' => 'test'
            )
        );

        $response = $client->verifyNotification($response);
        var_dump($response);
    }

    public function testExceptionShouldBeThrownIfTheCallbackNotificationHasAnInvalidHash()
    {
        $this->setExpectedException('\Symm\BitpayClient\Exception\CallbackBadHashException');

        $client = $this->getClient();

        $response = json_encode(
            array(
                'posData' => '{"posData":[],"hash":"INVALID_HASH"}',
            )
        );

        $client->verifyNotification($response);
    }

    public function testTheClientShouldAutomaticallyHashThePosDataWhenCreatingAnInvoice()
    {
        $client = $this->getClient();
        $mock = $this->setMockResponse($client, 'createInvoice');

        $client->createInvoice(array(
            'price'    => 0.0001,
            'currency' => 'BTC',
            'posData'  => '{"invoiceNo" : "12345"}',
            'itemDesc' => 'Test transaction'
        ));

        /** @var \Guzzle\Http\Message\EntityEnclosingRequest $request */
        $request = $mock->getReceivedRequests()[0];
        $posData = $request->getPostFields()->get('posData');

        $this->assertEquals(
            '{"posData":"{\"invoiceNo\" : \"12345\"}","hash":"3hdrr7DrAv4icywWZYeDDrnVM0_Jsd5TlnjwLOEhKOs"}',
            $posData
        );
    }

    public function testTheClientShouldThrowAnExceptionIfThePosDataIsTooLong()
    {
        $this->setExpectedException('\Symm\BitPayClient\Exception\PosDataLengthException');

        $client = $this->getClient();
        $this->setMockResponse($client, 'createInvoice');

        $client->createInvoice(array(
            'price'    => 0.0001,
            'currency' => 'BTC',
            'posData'  => str_repeat('X', 34),
            'itemDesc' => 'Test transaction'
        ));
    }

    public function testTheVerifyNotificationMethodShouldReturnAHydratedInvoiceModelIfValidResponse()
    {
        $client = $this->getClient();

        $invoice = array(
            'id'             => 'XXXXXX',
            'url'            => 'https://bitpay.com/invoice?id=XXXXXXXXXXXXXXXXXXXXXX',
            'posData'        => '{"posData":[],"hash":"QtIBmfjB2OvGrjfqt-9EE4JeWBzKEB_q8UHN6-If_3U"}',
            'status'         => 'new',
            'btcPrice'       => '0.0001',
            'price'          => '0.0001',
            'currency'       => 'BTC',
            'invoiceTime'    => '1392457276030',
            'expirationTime' => '1392458176030',
            'currentTime'    => '1392457276187'
        );

        $invoice = $client->verifyNotification(json_encode($invoice));

        $this->assertInstanceOf('\Symm\BitpayClient\Model\Invoice', $invoice);
    }
}
