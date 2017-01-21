<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountChangeDataService;

/**
 * Тестирует класс AccountChangeDataService
 */
class AccountChangeDataServiceTests extends TestCase
{
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства AccountChangeDataService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangeDataService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountChangeDataService::handle
     */
    public function testHandle()
    {
        \Yii::$app->user->logout();
        
        $service = new AccountChangeDataService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('accountChangeDataWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['accountChangeDataWidgetConfig']);
        $this->assertNotEmpty($result['accountChangeDataWidgetConfig']);
    }
}
