<?php

namespace Symm\BitpayClient\Tests;

use Symm\BitpayClient\Localisation\Language;

/**
 * LanguageTest
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
{
    public function testItReturnsAnArrayOfLanguageChoices()
    {
        $this->assertInternalType('array', Language::getLanguageChoices());
    }

    public function testItReturnsAnArrayOfAllowedLanguages()
    {
        $this->assertInternalType('array', Language::getAllowedLanguages());
    }
}