<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCategoriesMenuWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;

/**
 * Тестирует класс GetCategoriesMenuWidgetConfigService
 */
class GetCategoriesMenuWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetCategoriesMenuWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCategoriesMenuWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('categoriesMenuWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetCategoriesMenuWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetCategoriesMenuWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertInternalType('array', $result['categories']);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
