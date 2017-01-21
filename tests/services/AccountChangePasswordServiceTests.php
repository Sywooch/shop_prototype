<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountChangePasswordService;

/**
 * Тестирует класс AccountChangePasswordService
 */
class AccountChangePasswordServiceTests extends TestCase
{
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства AccountChangePasswordService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangePasswordService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountChangePasswordService::handle
     */
    public function testHandle()
    {
        \Yii::$app->user->logout();
        
        $service = new AccountChangePasswordService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('accountChangePasswordWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['accountChangePasswordWidgetConfig']);
        $this->assertNotEmpty($result['accountChangePasswordWidgetConfig']);
    }
}
