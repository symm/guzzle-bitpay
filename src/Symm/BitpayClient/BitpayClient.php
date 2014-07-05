<?php

namespace Symm\BitpayClient;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

use Symm\BitpayClient\Exception\CallbackBadHashException;
use Symm\BitpayClient\Exception\CallbackPosDataMissingException;
use Symm\BitpayClient\Exception\CallbackJsonMissingException;
use Symm\BitpayClient\Exception\CallbackInvalidJsonException;
use Symm\BitpayClient\Model\Invoice;
use Symm\BitpayClient\Model\CurrencyCollection;

/**
 * BitpayClient
 *
 * @method Invoice createInvoice() createInvoice($parameters) Create an Invoice
 * @method Invoice getInvoice() getInvoice($parameters) Get an Invoice
 * @method CurrencyCollection getRates() getRates()
 */
class BitpayClient extends Client
{
    /**
     * Create a new BitpayClient
     *
     * @param string            $baseUrl
     * @param array|Collection  $config
     */
    public function __construct($baseUrl = '', $config = array())
    {
        parent::__construct($baseUrl, $config);

        $this->setUserAgent('BitpayClient - https://github.com/symm/guzzle-bitpay');
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
        $client->setDefaultOption(
            'auth',
            array(
                $config['apiKey'],
                '',
                'Basic'
            )
        );

        return $client;
    }

    /**
     * Factory method for creating a new BitpayClient
     *
     * @param $apiKey
     *
     * @return BitpayClient
     */
    public static function createClient($apiKey)
    {
        return self::factory(array(
            'apiKey'   => $apiKey,
            'base_url' => 'https://bitpay.com/api'
        ));
    }

    /**
     * Factory method for creating a new BitpayClient using the test environment
     * http://blog.bitpay.com/2014/05/13/introducing-the-bitpay-test-environment.html
     *
     * @param $apiKey
     *
     * @return BitpayClient
     */
    public static function createTestClient($apiKey)
    {
        return self::factory(array(
            'apiKey'   => $apiKey,
            'base_url' => 'https://test.bitpay.com/api'
        ));
    }

    /**
     * Call from your notification handler to convert raw post data to an array containing invoice data
     *
     * @param string $jsonString The raw POST data from file_get_contents("php://input");
     *
     * @return Invoice
     *
     * @throws Exception\CallbackPosDataMissingException
     * @throws Exception\CallbackJsonMissingException
     * @throws Exception\CallbackInvalidJsonException
     * @throws Exception\CallbackBadHashException
     */
    public function verifyNotification($jsonString)
    {
        if (!$jsonString) {
            throw new CallbackJsonMissingException();
        }

        $json = json_decode($jsonString, true);

        if (!is_array($json)) {
            throw new CallbackInvalidJsonException();
        }

        if (!array_key_exists('posData', $json)) {
            throw new CallbackPosDataMissingException();
        }

        $posData = json_decode($json['posData'], true);
        if ($posData['hash'] != $this->generateHash(serialize($posData['posData']))) {
            throw new CallbackBadHashException();
        }

        $json['posData'] = $posData['posData'];

        return Invoice::fromArray($json);
    }

    /**
     * Generate Callback hash
     *
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
