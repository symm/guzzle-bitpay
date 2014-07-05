<?php

namespace Symm\BitpayClient\Model;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;

use Symm\BitpayClient\Exception\ImmutableObjectException;

/**
 * CurrencyCollection
 */
class CurrencyCollection implements ResponseClassInterface, \ArrayAccess, \Iterator, \Countable
{
    /**
     * @var array
     */
    private $currencies;

    /**
     * @var integer
     */
    private $position;

    /**
     * Constructor
     *
     * @param array $currencies
     */
    public function __construct($currencies)
    {
        $this->position   = 0;
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
        throw new ImmutableObjectException(get_class($this) .' is an immutable object');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new ImmutableObjectException(get_class($this) .' is an immutable object');
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

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->currencies);
    }
}
