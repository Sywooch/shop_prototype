<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AbstarctFrontendService;

/**
 * Тестирует класс AbstarctFrontendService
 */
class AbstarctFrontendServiceTests extends TestCase
{
    /**
     * Тестирует свойства AbstarctFrontendService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AbstarctFrontendService::class);
    }
}
