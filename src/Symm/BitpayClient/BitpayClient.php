<?php

namespace Symm\BitpayClient;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Http\Message\RequestInterface;

use Symm\BitpayClient\Model\Invoice;
use Symm\BitpayClient\Model\CurrencyCollection;
use Symm\BitpayClient\Exceptions\AuthenticationFailedException;
use Symm\BitpayClient\Exceptions\InvalidJsonResponseException;

/**
 * BitpayClient
 *
 * @method Invoice createInvoice() createInvoice($parameters) Create an Invoice
 * @method Invoice getInvoice() getInvoice($parameters) Get an Invoice
 * @method CurrencyCollection getRates() getRates()
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
     * Call from your notification handler to convert $_POST data to an array containing invoice data
     *
     * @param string $response The raw POST json string obtained using file_get_contents("php://input");
     *
     * @return Array The decoded response
     * @throws Exceptions\InvalidJsonResponseException
     * @throws Exceptions\AuthenticationFailedException
     */
    public function verifyNotification($response)
    {
        $jsonArray = json_decode($response, true);

        if (null === $jsonArray) {
            throw new InvalidJsonResponseException('Error decoding JSON: ' . json_last_error());
        }

        if (!array_key_exists('posData', $jsonArray)) {
            throw new InvalidJsonResponseException('Could not find the posData array key in the response');
        }

        if (!array_key_exists('hash', $jsonArray)) {
            throw new InvalidJsonResponseException('Could not find the hash array key in the response');
        }

        $posData = json_decode($jsonArray['posData'], true);
        if (null === $jsonArray) {
            throw new InvalidJsonResponseException('Error decoding posData: ' . json_last_error());
        }

        $expectedHash = $this->generateHash(serialize($posData['posData']));
        if ($posData['hash'] != $expectedHash) {
            $exception = new AuthenticationFailedException('Authentication failed - bad hash');

            throw $exception;
        }

        $jsonArray['posData'] = $posData['posData'];

        return $jsonArray;
    }

    /**
     * @param string $data
     *
     * @return string
     */
    private function generateHash($data)
    {
        $key = $this->getConfig('apiKey');
        $hmac = base64_encode(hash_hmac('sha256', $data, $key, true));

        return strtr($hmac, array('+' => '-', '/' => '_', '=' => ''));
    }

}