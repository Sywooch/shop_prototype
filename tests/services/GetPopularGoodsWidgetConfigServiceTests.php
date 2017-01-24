<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetPopularGoodsWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

/**
 * Тестирует класс GetPopularGoodsWidgetConfigService
 */
class GetPopularGoodsWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetPopularGoodsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetPopularGoodsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('popularGoodsWidgetArray'));
    }
    
    /**
     * Тестирует метод GetPopularGoodsWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetPopularGoodsWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('goods', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('array', $result['goods']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
