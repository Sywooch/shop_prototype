<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAverageBillWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\models\CurrencyModel;
use app\collections\PurchasesCollectionInterface;

/**
 * Тестирует класс GetAverageBillWidgetConfigService
 */
class GetAverageBillWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetAverageBillWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAverageBillWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('averageBillWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAverageBillWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetAverageBillWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
