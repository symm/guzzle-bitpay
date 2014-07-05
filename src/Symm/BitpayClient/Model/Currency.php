<?php

namespace Symm\BitpayClient\Model;

/**
 * Currency
 */
class Currency
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var double
     */
    private $rate;

    /**
     * Constructor
     *
     * @param string $code
     * @param string $name
     * @param string $rate
     */
    public function __construct($code, $name, $rate)
    {
        $this->code = $code;
        $this->name = $name;
        $this->rate = $rate;
    }

    /**
     * Get the ISO 4217 code of the Currency
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the name of the Currency
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the exchange rate of the Currency
     *
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }
}
