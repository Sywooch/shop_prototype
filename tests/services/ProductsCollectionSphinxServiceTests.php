<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ProductsCollectionSphinxService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\controllers\ProductsListController;
use app\collections\CollectionInterface;
use app\helpers\HashHelper;
use yii\helpers\Url;

/**
 * Тестирует класс ProductsCollectionSphinxService
 */
class ProductsCollectionSphinxServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductsCollectionSphinxService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsCollectionSphinxService::class);
        
        $this->assertTrue($reflection->hasProperty('productsCollection'));
    }
    
    /**
     * Тестирует метод ProductsCollectionSphinxService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new ProductsCollectionSphinxService();
        $service->handle();
    }
    
    /**
     * Тестирует метод ProductsCollectionSphinxService::handle
     * page === null
     * filters === null
     */
    public function testHandleOne()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'search') {
                    return 'пиджак';
                }
            }
        };
        
        $service = new ProductsCollectionSphinxService();
        $result = $service->handle($request);

        $this->assertInstanceOf(CollectionInterface::class, $result);
        $this->assertFalse($result->isEmpty());
    }
    
    /**
     * Тестирует метод ProductsCollectionSphinxService::handle
     * page === true
     * filters === null
     */
    public function testHandleTwo()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'search') {
                    return 'пиджак';
                }
                if ($name === 'page') {
                    return 1;
                }
            }
        };
        
        $service = new ProductsCollectionSphinxService();
        $result = $service->handle($request);

        $this->assertInstanceOf(CollectionInterface::class, $result);
        $this->assertFalse($result->isEmpty());
    }
    
    /**
     * Тестирует метод ProductsCollectionSphinxService::handle
     * page === null
     * filters === true
     */
    public function testHandleThree()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $key = HashHelper::createFiltersKey(Url::current());
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'colors'=>[1, 2, 3, 4, 5],
            'sizes'=>[1, 2, 3, 4, 5],
            'brands'=>[1, 2, 3, 4, 5],
        ]);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'search') {
                    return 'пиджак';
                }
            }
        };
        
        $service = new ProductsCollectionSphinxService();
        $result = $service->handle($request);

        $this->assertInstanceOf(CollectionInterface::class, $result);
        $this->assertFalse($result->isEmpty());
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
