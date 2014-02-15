<?php

namespace Symm\BitpayClient\Tests;

use Symm\BitpayClient\Model\Currency;

/**
 * CurrencyTest
 */
class CurrencyTest extends \PHPUnit_Framework_TestCase
{

    protected $currency = array(
        'code' => 'SEK',
        'name' => 'Swedish Krona',
        'rate' => 4169.1658
    );

    public function getCurrencyObject()
    {
        return new Currency(
            $this->currency['code'],
            $this->currency['name'],
            $this->currency['rate']
        );
    }

    public function testItCanBeInstantiated()
    {
        $currency = $this->getCurrencyObject();
        $this->assertInstanceOf('Symm\BitpayClient\Model\Currency', $currency);
    }

    public function testItReturnsACurrencyCode()
    {
        $currency = $this->getCurrencyObject();
        $this->assertEquals($this->currency['code'], $currency->getCode());
    }

    public function testItReturnsAItsName()
    {
        $currency = $this->getCurrencyObject();
        $this->assertEquals($this->currency['name'], $currency->getName());
    }

    public function testItReturnsTheRate()
    {
        $currency = $this->getCurrencyObject();
        $this->assertEquals($this->currency['rate'], $currency->getRate());
    }
}
