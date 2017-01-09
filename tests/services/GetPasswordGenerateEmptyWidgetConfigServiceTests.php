<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetPasswordGenerateEmptyWidgetConfigService;

/**
 * Тестирует класс GetPasswordGenerateEmptyWidgetConfigService
 */
class GetPasswordGenerateEmptyWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует класс GetPasswordGenerateEmptyWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetPasswordGenerateEmptyWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('passwordGenerateEmptyWidgetArray'));
    }
    
    /**
     * Тестирует метод GetPasswordGenerateEmptyWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetPasswordGenerateEmptyWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
}
