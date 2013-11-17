<?php

namespace Symm\BitpayClient\Tests;

use Symm\BitpayClient\BitpayClient;

class BitpayClientTest extends \Guzzle\Tests\GuzzleTestCase
{
    private function getClient()
    {
        $config = array(
            'apiKey' => 'test'
        );
        $client = BitpayClient::factory($config);

        return $client;

    }

    public function testBuilderCreatesClient()
    {
        $config = array(
            'apiKey' => 'test'
        );
        $client = BitpayClient::factory($config);

        $this->assertInstanceOf('\Symm\BitpayClient\BitpayClient', $client);
    }

    /**
     * @expectedException Symm\BitpayClient\Exceptions\InvalidJsonResponseException
     */
    public function testVerifyNotificationBadJson()
    {
        $client = $this->getClient();
        $response = 'bad json';
        $client->verifyNotification($response);
    }

    /**
     * @expectedException Symm\BitpayClient\Exceptions\InvalidJsonResponseException
     */
    public function testVerifyNotificationMissingPosdata()
    {
        $client = $this->getClient();
        $response = array(
            'test' => true
        );
        $response = json_encode($response);

        $client->verifyNotification($response);
    }

    /**
     * @expectedException Symm\BitpayClient\Exceptions\InvalidJsonResponseException
     */
    public function testVerifyNotificationMissingHashData()
    {
        $client = $this->getClient();
        $response = array(
            'posData' => ''
        );
        $response = json_encode($response);

        $client->verifyNotification($response);

    }

    /**
     * @expectedException Symm\BitpayClient\Exceptions\InvalidJsonResponseException
     */
    public function testVerifyNotificationEmptyPosData()
    {

        $client = $this->getClient();
        $response = array(
            'posData' => '',
        );
        $response = json_encode($response);

        $client->verifyNotification($response);
    }

    /**
     * @expectedException Symm\BitpayClient\Exceptions\AuthenticationFailedException
     */
    public function testVerifyNoticationBadHash()
    {
        $client = $this->getClient();
        $response = array(
            'posData' => 'order 1234',
            'hash'    => 'badhash',
        );
        $response = json_encode($response);

        $client->verifyNotification($response);
    }

}
