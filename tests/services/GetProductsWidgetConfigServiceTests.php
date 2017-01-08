<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetProductsWidgetConfigService;
use app\controllers\ProductsListController;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\collections\ProductsCollectionInterface;
use app\models\CurrencyModel;

/**
 * Тестирует класс GetProductsWidgetConfigService
 */
class GetProductsWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetProductsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetProductsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('productsWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetProductsWidgetConfigService::handle
     * если нет $request
     * @expectedException ErrorException
     */
    public function testHandleRequestError()
    {
        $service = new GetProductsWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetProductsWidgetConfigService::handle
     */
    public function testGetProductsArray()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public function get($name)
            {
                return null;
            }
        };
        
        $service = new GetProductsWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(ProductsCollectionInterface::class, $result['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
