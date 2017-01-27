<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetUnsubscribeSuccessWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;

/**
 * Тестирует класс GetUnsubscribeSuccessWidgetConfigService
 */
class GetUnsubscribeSuccessWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetUnsubscribeSuccessWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetUnsubscribeSuccessWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('unsubscribeSuccessWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetUnsubscribeSuccessWidgetConfigService::handle
     * если пуст $request
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: request
     */
    public function testHandleEmptyRequest()
    {
        $request = [];
        
        $service = new GetUnsubscribeSuccessWidgetConfigService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetUnsubscribeSuccessWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['mailingsIdArray'=>[1, 2, 3, 4]];
        
        $service = new GetUnsubscribeSuccessWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
