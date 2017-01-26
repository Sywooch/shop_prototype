<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AdminOrdersCollectionService;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\collections\PurchasesCollection;
use app\helpers\HashHelper;

/**
 * Тестирует класс AdminOrdersCollectionService
 */
class AdminOrdersCollectionServiceTests extends TestCase
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
     * Тестирует свойства AdminOrdersCollectionService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersCollectionService::class);
        
        $this->assertTrue($reflection->hasProperty('purchasesCollection'));
    }
    
    /**
     * Тестирует метод AdminOrdersCollectionService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AdminOrdersCollectionService();
        $service->handle();
    }
    
    /**
     * Тестирует метод AdminOrdersCollectionService::handle
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
        
        $service = new AdminOrdersCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrdersCollectionService::handle
     * page === true
     */
    public function testHandlePage()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 2;
            }
        };
        
        $service = new AdminOrdersCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }

    /**
     * Тестирует метод AdminOrdersCollectionService::handle
     * page === null
     * filters === true
     */
    public function testHandleFilters()
    {
        $key = HashHelper::createHash([\Yii::$app->params['adminOrdersFilters']]);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'status'=>'shipped'
        ]);

        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new AdminOrdersCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(PurchasesCollection::class, $result);

        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
