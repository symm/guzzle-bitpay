<?php

namespace Symm\BitpayClient\Tests;

use Symm\BitpayClient\Model\Invoice;

/**
 * InvoiceTest
 */
class InvoiceTest extends \PHPUnit_Framework_TestCase
{
    protected $params = array();

    protected $json = array(
        "id"             => "XXXXXXXXXXXXXXXXXXXXXX",
        "url"            => "https://bitpay.com/invoice?id=XXXXXXXXXXXXXXXXXXXXXX",
        "posData"        => "Test Pos data",
        "status"         => "new",
        "btcPrice"       => "0.0001",
        "price"          => 0.0001,
        "currency"       => "BTC",
        "invoiceTime"    => 1392457276030,
        "expirationTime" => 1392458176030,
        "currentTime"    => 1392457276187
    );

    protected function getMockResponse()
    {
        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()->getMock();
        $response->expects($this->any())
            ->method('json')
            ->will($this->returnValue($this->json));

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

    protected function millisecondsToUnixTime($milliseconds)
    {
        return round($milliseconds / 1000);
    }

    public function testInvoiceCanBeInstantiated()
    {
        $invoice = Invoice::fromCommand($this->getMockOperationCommand());
        $this->assertInstanceOf('\Symm\BitpayClient\Model\Invoice', $invoice);
    }

    public function testInvoiceImplementsResponseClassInterface()
    {
        $invoice = Invoice::fromCommand($this->getMockOperationCommand());
        $this->assertInstanceOf('\Guzzle\Service\Command\ResponseClassInterface', $invoice);
    }

    public function testInvoicePropertiesAreMapped()
    {
        $invoice = Invoice::fromCommand($this->getMockOperationCommand());

        $this->assertEquals($this->json['id'], $invoice->getId());
        $this->assertEquals($this->json['url'], $invoice->getUrl());
        $this->assertEquals($this->json['posData'], $invoice->getPosData());
        $this->assertEquals($this->json['status'], $invoice->getStatus());
        $this->assertEquals($this->json['btcPrice'], $invoice->getBtcPrice());
        $this->assertEquals($this->json['price'], $invoice->getPrice());
        $this->assertEquals($this->json['currency'], $invoice->getCurrency());

        $this->assertInstanceOf('\DateTime', $invoice->getExpirationTime());
        $this->assertInstanceOf('\DateTime', $invoice->getCurrentTime());
        $this->assertInstanceOf('\DateTime', $invoice->getInvoiceTime());

        $this->assertEquals(
            $this->millisecondsToUnixTime($this->json['expirationTime']),
            $invoice->getExpirationTime()->format('U')
        );
        $this->assertEquals(
            $this->millisecondsToUnixTime($this->json['currentTime']),
            $invoice->getCurrentTime()->format('U')
        );
        $this->assertEquals(
            $this->millisecondsToUnixTime($this->json['invoiceTime']),
            $invoice->getInvoiceTime()->format('U')
        );
    }

    public function testInvoiceHasAnArrayOfInvoiceStatuses()
    {
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, Invoice::$allowedStatuses);
    }
}
