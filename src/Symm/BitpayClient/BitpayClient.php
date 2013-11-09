<?php

namespace Symm\BitpayClient;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Http\Message\RequestInterface;


/**
 * Class BitpayClient
 *
 * @package Symm\BitpayClient
 */
class BitpayClient extends Client
{

    const CLIENT_VERSION = 'beta';

    /**
     * Create a new BitpayClient
     *
     * @param string $baseUrl
     * @param null   $config
     */
    public function __construct($baseUrl = '', $config = null)
    {
        parent::__construct($baseUrl, $config);

        //$this->setUserAgent('BitpayClient: ' . self::CLIENT_VERSION . '- https://github.com/symm/guzzle-bitpay');
    }

    /**
     * Factory method to create a new Bitpay client
     *
     * @param array $config
     *
     * @return BitpayClient
     */
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => 'https://bitpay.com/api',
        );

        $required = array(
            'apiKey'
        );

        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);
        $client->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . 'client.json'));
        $client->setDefaultOption('auth', array($client->getconfig('apiKey'), '', 'Basic'));

        return $client;
    }


    /**
     * @param string $method
     * @param null   $uri
     * @param null   $headers
     * @param null   $body
     * @param array  $options
     *
     * @return RequestInterface
     */
    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null, array $options = array())
    {
        $request = parent::createRequest($method, $uri, $headers, $body);

        return $request;
    }

}