<?php

namespace Symm\BitpayClient\Model;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;

/**
 * CurrencyCollection
 */
class CurrencyCollection implements ResponseClassInterface, \ArrayAccess, \Iterator
{
    /**
     * @var array
     */
    protected $currencies;

    /**
     * @var integer
     */
    protected $position;

    /**
     * Constructor
     *
     * @param $currencies
     */
    public function __construct($currencies)
    {
        $this->position = 0;
        $this->currencies = $currencies;
    }

    /**
     * Create a response model object from a completed command
     *
     * @param OperationCommand $command That serialized the request
     *
     * @return CurrencyCollection
     */
    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse();
        $json     = $response->json();

        $currencies = array();
        foreach ($json as $currency) {
            $currencies[] = new Currency($currency['code'], $currency['name'], $currency['rate']);
        }

        return new self($currencies);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->currencies[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->currencies[$offset];
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($offset) {
            $this->currencies[$offset] = $value;
        } else {
            $this->currencies[] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->currencies[$offset]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->currencies[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return $this->position++;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->currencies[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
