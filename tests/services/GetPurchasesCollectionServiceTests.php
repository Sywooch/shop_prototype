<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetPurchasesCollectionService;
use app\tests\sources\fixtures\PurchasesFixture;
use app\tests\DbManager;
use app\collections\PurchasesCollection;

/**
 * Тестирует класс GetPurchasesCollectionService
 */
class GetPurchasesCollectionServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetPurchasesCollectionService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetPurchasesCollectionService::class);
        
        $this->assertTrue($reflection->hasProperty('purchasesCollection'));
    }
    
    /**
     * Тестирует метод GetPurchasesCollectionService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new GetPurchasesCollectionService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetPurchasesCollectionService::handle
     * page === null
     */
    public function testHandleClean()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new GetPurchasesCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод GetPurchasesCollectionService::handle
     * page === true
     */
    public function testHandleCategory()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 2;
            }
        };
        
        $service = new GetPurchasesCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
