<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetMailingsWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;

/**
 * Тестирует класс GetMailingsWidgetConfigService
 */
class GetMailingsWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetMailingsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetMailingsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('mailingsWidgetArray'));
    }
    
    /**
     * Тестирует метод GetMailingsWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetMailingsWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
