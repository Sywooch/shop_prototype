<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetEmailMailingWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;

/**
 * Тестирует класс GetEmailMailingWidgetConfigService
 */
class GetEmailMailingWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetEmailMailingWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetEmailMailingWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('emailMailingWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetEmailMailingWidgetConfigService::handle
     * если пуст $request[email]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyEmail()
    {
        $request = [];
        
        $service = new GetEmailMailingWidgetConfigService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetEmailMailingWidgetConfigService::handle
     * если пуст $request[diffIdArray]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: diffIdArray
     */
    public function testHandleEmptyDiffIdArray()
    {
        $request = ['email'=>'some@some.com'];
        
        $service = new GetEmailMailingWidgetConfigService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetEmailMailingWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['email'=>'some@some.com', 'diffIdArray'=>[1, 2, 3, 4]];
        
        $service = new GetEmailMailingWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('key', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['key']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
