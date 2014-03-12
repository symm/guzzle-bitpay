<?php

namespace Symm\BitpayClient\Tests;

use Symm\BitpayClient\Localisation\Language;

/**
 * LanguageTest
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
{

    public function test_it_returns_an_array_of_language_choices()
    {
        $this->assertInternalType('array', Language::getLanguageChoices());
    }

    public function test_it_returns_an_array_of_allowed_languages()
    {
        $this->assertInternalType('array', Language::getAllowedLanguages());
    }
}