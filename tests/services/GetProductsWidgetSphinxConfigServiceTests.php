<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetProductsWidgetSphinxConfigService;
use app\controllers\ProductsListController;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\collections\CollectionInterface;
use app\models\CurrencyModel;

/**
 * Тестирует класс GetProductsWidgetSphinxConfigService
 */
class GetProductsWidgetSphinxConfigServiceTests extends TestCase
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
     * Тестирует свойства GetProductsWidgetSphinxConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetProductsWidgetSphinxConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('productsWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetProductsWidgetSphinxConfigService::handle
     * если нет $request
     * @expectedException ErrorException
     */
    public function testHandleRequestError()
    {
        $service = new GetProductsWidgetSphinxConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetProductsWidgetSphinxConfigService::handle
     */
    public function testGetProductsArray()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public function get($name)
            {
                return 'пиджак';
            }
        };
        
        $service = new GetProductsWidgetSphinxConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(CollectionInterface::class, $result['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
