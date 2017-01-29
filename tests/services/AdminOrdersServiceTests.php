<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AdminOrdersService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\controllers\FiltersController;

/**
 * Тестирует класс AdminOrdersService
 */
class AdminOrdersServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminOrdersService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminOrdersService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AdminOrdersService();
        $service->handle();
    }
    
    /**
     * Тестирует метод AdminOrdersService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new FiltersController('filters', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new AdminOrdersService();
        $result = $service->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('оrdersFiltersWidgetConfig', $result);
        $this->assertArrayHasKey('adminOrdersFormWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['оrdersFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['adminOrdersFormWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
