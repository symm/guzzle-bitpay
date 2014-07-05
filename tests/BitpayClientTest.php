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

    /**
     * @expectedException \Guzzle\Common\Exception\InvalidArgumentException
     */
    public function testApiKeyIsARequiredArguement()
    {
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

    public function getRatesShouldReturnACurrencyCollection()
    {
        $client = $this->getClient();
        $this->setMockResponse($client, 'getRates');
        $rates = $client->getRates();

        $this->assertInstanceOf('\Symm\BitpayClient\Model\CurrencyCollection', $rates);
    }

    /**
     * @expectedException \Symm\BitpayClient\Exception\CallbackJsonMissingException
     */
    public function testExceptionShouldBeThrownIfTheCallbackNotifcationIsBlank()
    {
        $client = $this->getClient();

        $client->verifyNotification('');
    }

    /**
     * @expectedException \Symm\BitpayClient\Exception\CallbackInvalidJsonException
     */
    public function testExceptionShouldBeThrownIfTheCallbackNotificationIsNotValidJson()
    {
        $client = $this->getClient();

        $client->verifyNotification("23kwreGSFDG£%");
    }

    /**
     * @expectedException \Symm\BitpayClient\Exception\CallbackPosDataMissingException
     */
    public function testExceptionShouldBeThrownIfTheCallbackNotificationIsMissingThePosData()
    {
        $client   = $this->getClient();
        $response = json_encode(
            array(
                'id' => 'test'
            )
        );

        $response = $client->verifyNotification($response);
        var_dump($response);
    }

    /**
     * @expectedException \Symm\BitpayClient\Exception\CallbackBadHashException
     */
    public function testExceptionShouldBeThrownIfTheCallbackNotificationHasAnInvalidHash()
    {
        $client = $this->getClient();

        $response = json_encode(
            array(
                'posData' => '{"posData":[],"hash":"INVALID_HASH"}',
            )
        );

        $client->verifyNotification($response);
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
