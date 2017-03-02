<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\CurrencyHelper;

/**
 * Тестирует класс CurrencyHelper
 */
class CurrencyHelperTests extends TestCase
{
    /**
     * Тестирует метод CurrencyHelper::exchangeRate
     */
    public function testExchangeRate()
    {
        $result = CurrencyHelper::exchangeRate('RUB', 'UAH');
        
        $this->assertInternalType('float', $result);
    }
}
