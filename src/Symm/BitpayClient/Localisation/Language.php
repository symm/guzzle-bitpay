<?php

namespace Symm\BitpayClient\Localisation;

/**
 * Language
 */
class Language
{
    const ARABIC              = 'ar_SA';
    const BULGARIAN           = 'bg_BG';
    const CATALAN             = 'ca_ES';
    const CZECH               = 'cs_CZ';
    const DANISH              = 'da_DK';
    const GERMAN              = 'de_DE';
    const GREEK               = 'el_GR';
    const ENGLISH             = 'en';
    const SPANISH             = 'es';
    const ESTONIAN            = 'et_EE';
    const PERSIAN             = 'fa_IR';
    const FINNISH             = 'fi_FI';
    const FRENCH              = 'fr_FR';
    const HEBREW              = 'he_IL';
    const INDONESIAN          = 'id_ID';
    const ITALIAN             = 'it_IT';
    const JAPANESE            = 'ja_JP';
    const KOREAN              = 'ko_KR';
    const LITHUANIAN          = 'lt_LT';
    const LATVIAN             = 'lv_LV';
    const MALAY               = 'ms_MY';
    const NORWEGIAN           = 'nb_NO';
    const DUTCH               = 'nl_NL';
    const POLISH              = 'pl_PL';
    const PORTUGUESE          = 'pt_BR';
    const LEET                = 'pwn';
    const ROMANIAN            = 'ro_RO';
    const RUSSIAN             = 'ru_RU';
    const SLOVAK              = 'sk_SK';
    const SLOVENE             = 'sl_SI';
    const SERBIAN             = 'sr_RS';
    const SWEDISH             = 'sv_SE';
    const THAI                = 'th_TH';
    const TURKISH             = 'tr';
    const UKRAINIAN           = 'uk_UA';
    const VIETNAMESE          = 'vi_VN';
    const CHINESE             = 'zh_CN';
    const TRADITIONAL_CHINESE = 'zh_TW';

    private static $allowedLanguages = array(
        self::ARABIC,
        self::BULGARIAN,
        self::CATALAN,
        self::CZECH,
        self::DANISH,
        self::GERMAN,
        self::GREEK,
        self::ENGLISH,
        self::SPANISH,
        self::ESTONIAN,
        self::PERSIAN,
        self::FINNISH,
        self::FRENCH,
        self::HEBREW,
        self::INDONESIAN,
        self::ITALIAN,
        self::JAPANESE,
        self::KOREAN,
        self::LITHUANIAN,
        self::LATVIAN,
        self::MALAY,
        self::NORWEGIAN,
        self::DUTCH,
        self::POLISH,
        self::PORTUGUESE,
        self::LEET,
        self::ROMANIAN,
        self::RUSSIAN,
        self::SLOVAK,
        self::SLOVENE,
        self::SERBIAN,
        self::SWEDISH,
        self::THAI,
        self::TURKISH,
        self::UKRAINIAN,
        self::VIETNAMESE,
        self::CHINESE,
        self::TRADITIONAL_CHINESE
    );

    private static $languageChoices = array(
        self::ARABIC              => 'العربية',
        self::BULGARIAN           => 'Български',
        self::CATALAN             => 'Català',
        self::CZECH               => 'Čeština',
        self::DANISH              => 'Dansk',
        self::GERMAN              => 'Deutsch',
        self::GREEK               => 'Ελληνικά',
        self::ENGLISH             => 'English',
        self::SPANISH             => 'Español',
        self::ESTONIAN            => 'Eesti',
        self::PERSIAN             => 'فارسی',
        self::FINNISH             => 'Suomi',
        self::FRENCH              => 'Français',
        self::HEBREW              => 'עברית',
        self::INDONESIAN          => 'Bahasa indonesia',
        self::ITALIAN             => 'Italiano',
        self::JAPANESE            => '日本語',
        self::KOREAN              => '한국어',
        self::LITHUANIAN          => 'Lietuviškai',
        self::LATVIAN             => 'Latviešu',
        self::MALAY               => 'Bahasa melayu',
        self::NORWEGIAN           => 'Norsk',
        self::DUTCH               => 'Nederlands',
        self::POLISH              => 'Polski',
        self::PORTUGUESE          => 'Português',
        self::LEET                => 'T3H L337 5P34K',
        self::ROMANIAN            => 'Română',
        self::RUSSIAN             => 'Русский',
        self::SLOVAK              => 'Slovenčina',
        self::SLOVENE             => 'Slovenščina',
        self::SERBIAN             => 'Српски (Srpski)',
        self::SWEDISH             => 'Svenska',
        self::THAI                => 'ภาษาไทย',
        self::TURKISH             => 'Türkçe',
        self::UKRAINIAN           => 'Українська',
        self::VIETNAMESE          => 'Tiếng Việt',
        self::CHINESE             => '中文',
        self::TRADITIONAL_CHINESE => '繁體中文',
    );

    /**
     * Get the allowed languages
     *
     * @return array
     */
    public static function getAllowedLanguages()
    {
        return self::$allowedLanguages;
    }

    /**
     * Get the language choices
     *
     * @return array
     */
    public static function getLanguageChoices()
    {
        return self::$languageChoices;
    }
}
