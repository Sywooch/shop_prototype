<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAccountChangePasswordWidgetConfigService;
use app\forms\UserChangePasswordForm;

/**
 * Тестирует класс GetAccountChangePasswordWidgetConfigService
 */
class GetAccountChangePasswordWidgetConfigServiceTests extends TestCase
{
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetAccountChangePasswordWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAccountChangePasswordWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('accountChangePasswordWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAccountChangePasswordWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetAccountChangePasswordWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInstanceOf(UserChangePasswordForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
}
