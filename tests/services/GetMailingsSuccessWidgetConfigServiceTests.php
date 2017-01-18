<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetMailingsSuccessWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;

/**
 * Тестирует класс GetMailingsSuccessWidgetConfigService
 */
class GetMailingsSuccessWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetMailingsSuccessWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetMailingsSuccessWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('mailingsSuccessWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetMailingsSuccessWidgetConfigService::handle
     * если пуст $request
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: request
     */
    public function testHandleEmptyRequest()
    {
        $request = [];
        
        $service = new GetMailingsSuccessWidgetConfigService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetMailingsSuccessWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['diffIdArray'=>[1, 2, 3, 4]];
        
        $service = new GetMailingsSuccessWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
