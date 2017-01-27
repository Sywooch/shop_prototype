<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetShortCartWidgetConfigRedirectService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\collections\PurchasesCollectionInterface;
use app\models\CurrencyModel;

/**
 * Тестирует класс GetShortCartWidgetConfigRedirectService
 */
class GetShortCartWidgetConfigRedirectServiceTests extends TestCase
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
     * Тестирует свойства GetShortCartWidgetConfigRedirectService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetShortCartWidgetConfigRedirectService::class);
        
        $this->assertTrue($reflection->hasProperty('shortCartWidgetArray'));
    }
    
    /**
     * Тестирует метод GetShortCartWidgetConfigRedirectService::handle
     */
    public function testHandle()
    {
        $service = new GetShortCartWidgetConfigRedirectService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
