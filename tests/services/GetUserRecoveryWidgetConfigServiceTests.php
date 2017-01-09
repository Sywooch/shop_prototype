<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetUserRecoveryWidgetConfigService;
use app\forms\RecoveryPasswordForm;

/**
 * Тестирует класс GetUserRecoveryWidgetConfigService
 */
class GetUserRecoveryWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetUserRecoveryWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetUserRecoveryWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('userRecoveryWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetUserRecoveryWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetUserRecoveryWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(RecoveryPasswordForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
}
