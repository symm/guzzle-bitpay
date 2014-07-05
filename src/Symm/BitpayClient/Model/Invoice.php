<?php

namespace Symm\BitpayClient\Model;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;

use Symm\BitpayClient\Localisation\Language;

/**
 * Invoice
 */
class Invoice implements ResponseClassInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var  string
     */
    private $url;

    /**
     * @var string
     */
    private $posData;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $btcPrice;

    /**
     * @var double
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var \DateTime
     */
    private $invoiceTime;

    /**
     * @var \DateTime
     */
    private $expirationTime;

    /**
     * @var \DateTime
     */
    private $currentTime;

    const STATUS_NEW       = 'new';
    const STATUS_PAID      = 'paid';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETE  = 'complete';
    const STATUS_EXPIRED   = 'expired';
    const STATUS_INVALID   = 'invalid';

    /**
     * The allowed invoice statuses
     *
     * @var array
     */
    public static $allowedStatuses = array(
        self::STATUS_NEW,
        self::STATUS_PAID,
        self::STATUS_CONFIRMED,
        self::STATUS_COMPLETE,
        self::STATUS_EXPIRED,
        self::STATUS_INVALID
    );

    /**
     * Constructor
     *
     * @param string    $id
     * @param string    $url
     * @param string    $posData
     * @param string    $status
     * @param string    $btcPrice
     * @param double    $price
     * @param string    $currency
     * @param \DateTime $invoiceTime
     * @param \DateTime $expirationTime
     * @param \DateTime $currentTime
     */
    public function __construct(
        $id,
        $url,
        $posData,
        $status,
        $btcPrice,
        $price,
        $currency,
        \DateTime $invoiceTime,
        \DateTime $expirationTime,
        \DateTime $currentTime
    ) {

        $this->id             = $id;
        $this->url            = $url;
        $this->posData        = $posData;
        $this->status         = $status;
        $this->btcPrice       = $btcPrice;
        $this->price          = $price;
        $this->currency       = $currency;
        $this->invoiceTime    = $invoiceTime;
        $this->expirationTime = $expirationTime;
        $this->currentTime    = $currentTime;
    }

    /**
     * Create a response model object from a completed command
     *
     * @param OperationCommand $command That serialized the request
     *
     * @return self
     */
    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse();
        $json     = $response->json();

        return self::fromArray($json);
    }

    public static function fromArray($json)
    {
        return new self(
            $json['id'],
            $json['url'],
            array_key_exists('posData', $json) ? $json['posData'] : '', // posData key is not always returned by api
            $json['status'],
            $json['btcPrice'],
            $json['price'],
            $json['currency'],
            self::parseDate($json['invoiceTime']),
            self::parseDate($json['expirationTime']),
            self::parseDate($json['currentTime'])
        );
    }

    /**
     * Convert milliseconds into a DateTime object
     *
     * @param integer $milliseconds
     *
     * @return \DateTime
     */
    public static function parseDate($milliseconds)
    {
        return new \DateTime('@' . round($milliseconds / 1000));
    }

    /**
     * Get the btcPrice of the Invoice
     *
     * @return string
     */
    public function getBtcPrice()
    {
        return $this->btcPrice;
    }

    /**
     * Get the currency of the Invoice
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get the currentTime of the Invoice
     *
     * @return \DateTime
     */
    public function getCurrentTime()
    {
        return $this->currentTime;
    }

    /**
     * Get the expirationTime of the Invoice
     *
     * @return \DateTime
     */
    public function getExpirationTime()
    {
        return $this->expirationTime;
    }

    /**
     * Get the id of the Invoice
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the invoiceTime of the Invoice
     *
     * @return \DateTime
     */
    public function getInvoiceTime()
    {
        return $this->invoiceTime;
    }

    /**
     * Get the posData of the Invoice
     *
     * @return string
     */
    public function getPosData()
    {
        return $this->posData;
    }

    /**
     * Get the price of the Invoice
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get the status of the Invoice
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the url of the Invoice
     *
     * @param null $languageCode
     *
     * @return string
     */
    public function getUrl($languageCode = null)
    {
        if ($languageCode && in_array($languageCode, Language::getAllowedLanguages())) {
            return $this->url . '&lang=' . $languageCode;
        }

        return $this->url;
    }
}
