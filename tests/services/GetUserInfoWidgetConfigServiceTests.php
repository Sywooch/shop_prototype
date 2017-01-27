<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetUserInfoWidgetConfigService;
use yii\web\User;

/**
 * Тестирует класс GetUserInfoWidgetConfigService
 */
class GetUserInfoWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetUserInfoWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetUserInfoWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('userInfoWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetUserInfoWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetUserInfoWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertInternalType('string', $result['template']);
    }
}
