<?php

namespace Symm\BitpayClient\Tests;

use Symm\BitpayClient\Model\CurrencyCollection;

/**
 * CurrencyCollectionTest
 */
class CurrencyCollectionTest extends \PHPUnit_Framework_TestCase
{
    protected $currencies = array(
        array(
            "code" => "USD",
            "name" => "US Dollar",
            "rate" => 658.36
        ),
        array(
            "code" => "EUR",
            "name" => "Eurozone Euro",
            "rate" => 472.3825
        ),
        array(
            "code" => "GBP",
            "name" => "Pound Sterling",
            "rate" => 386.2567
        )
    );

    protected function getMockResponse()
    {
        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()->getMock();
        $response->expects($this->any())
            ->method('json')
            ->will($this->returnValue($this->currencies));

        return $response;
    }

    protected function getMockOperationCommand()
    {
        $response = $this->getMockResponse();
        $command  = $this->getMock('Guzzle\Service\Command\OperationCommand');
        $command->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($response));

        return $command;
    }

    public function testItCanBeInstantiated()
    {
        $currencyCollection = CurrencyCollection::fromCommand($this->getMockOperationCommand());
        $this->assertInstanceOf('Symm\BitpayClient\Model\CurrencyCollection', $currencyCollection);
    }

    public function testItImplementsResponseClassInterface()
    {
        $currencyCollection = CurrencyCollection::fromCommand($this->getMockOperationCommand());
        $this->assertInstanceOf('\Guzzle\Service\Command\ResponseClassInterface', $currencyCollection);
    }

    public function testItIsIterable()
    {
        $currencyCollection = CurrencyCollection::fromCommand($this->getMockOperationCommand());
        foreach ($currencyCollection as $currency) {
            $this->assertInstanceOf('Symm\BitpayClient\Model\Currency', $currency);
        }
    }

    public function testItMapsTheCurrenciesCorrectly()
    {
        $currencyCollection = CurrencyCollection::fromCommand($this->getMockOperationCommand());

        $this->assertEquals(3, count($currencyCollection));

        $loopIndex = 0;
        foreach ($currencyCollection as $currencyObject) {
            $this->assertEquals($this->currencies[$loopIndex]['code'], $currencyObject->getCode());
            $this->assertEquals($this->currencies[$loopIndex]['name'], $currencyObject->getName());
            $this->assertEquals($this->currencies[$loopIndex]['rate'], $currencyObject->getRate());
            $this->assertEquals($loopIndex, $currencyCollection->key());

            $loopIndex++;
        }
    }

    public function testItReturnsCurrencyFromAnArrayKey()
    {
        $index = 1;
        $currencyCollection = CurrencyCollection::fromCommand($this->getMockOperationCommand());
        $this->assertEquals($this->currencies[$index]['code'], $currencyCollection[$index]->getCode());
    }

    public function testItReturnsFalseForAnEmptyArrayKey()
    {
        $currencyCollection = CurrencyCollection::fromCommand($this->getMockOperationCommand());
        $this->assertEquals(false, $currencyCollection[3]);
    }

    /**
     * @expectedException \Symm\BitpayClient\Exception\ImmutableObjectException
     */
    public function testTheArrayKeysCannotBeUpdated()
    {
        $currencyCollection = CurrencyCollection::fromCommand($this->getMockOperationCommand());
        $currencyCollection[0] = null;
    }

    /**
     * @expectedException \Symm\BitpayClient\Exception\ImmutableObjectException
     */
    public function testTheArrayKeysCannotBeUnset()
    {
        $currencyCollection = CurrencyCollection::fromCommand($this->getMockOperationCommand());
        unset($currencyCollection[0]);
    }
}
