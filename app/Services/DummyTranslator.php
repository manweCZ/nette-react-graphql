<?php
/**
 * User: Manwe
 */

namespace App\Services;


class DummyTranslator implements \Nette\Localization\Translator
{
    public function translate($message, ...$parameters): string
    {
        return $message;
    }
}