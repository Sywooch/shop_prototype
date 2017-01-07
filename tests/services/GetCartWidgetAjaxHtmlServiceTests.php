<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCartWidgetAjaxHtmlService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

/**
 * Тестирует класс GetCartWidgetAjaxHtmlService
 */
class GetCartWidgetAjaxHtmlServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetCartWidgetAjaxHtmlService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCartWidgetAjaxHtmlService::class);
        
        $this->assertTrue($reflection->hasProperty('cartWidgetAjaxArray'));
    }
    
    /**
     * Тестирует метод  GetCartWidgetAjaxHtmlService::handle
     */
    public function testHandle()
    {
        $service = new GetCartWidgetAjaxHtmlService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('cartInfo', $result);
        $this->assertInternalType('string', $result['cartInfo']);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
