<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ProductsCollectionService;
use app\tests\sources\fixtures\ProductsFixture;
use app\tests\DbManager;
use app\controllers\ProductsListController;
use app\collections\ProductsCollection;
use app\helpers\HashHelper;
use yii\helpers\Url;

/**
 * Тестирует класс ProductsCollectionService
 */
class GetProductsCollectionServiceTests extends TestCase
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
     * Тестирует свойства ProductsCollectionService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsCollectionService::class);
        
        $this->assertTrue($reflection->hasProperty('productsCollection'));
    }
    
    /**
     * Тестирует метод ProductsCollectionService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new ProductsCollectionService();
        $service->handle();
    }
    
    /**
     * Тестирует метод ProductsCollectionService::handle
     * category === null
     * subcategory === null
     * page === null
     * filters === null
     */
    public function testHandleClean()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new ProductsCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(ProductsCollection::class, $result);
        $this->assertFalse($result->isEmpty());
    }
    
    /**
     * Тестирует метод ProductsCollectionService::handle
     * category === true
     * subcategory === true
     * page === true
     * filters === null
     */
    public function testHandleCategory()
    {
        $request = new class() {
            public $category;
            public $subcategory;
            public $page;
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'category') {
                    return $this->category;
                }
                if ($name === 'subcategory') {
                    return $this->subcategory;
                }
                if ($name === 'page') {
                    return $this->page;
                }
            }
        };
        $reflection = new \ReflectionProperty($request, 'category');
        $reflection->setValue($request, self::$dbClass->categories['category_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'subcategory');
        $reflection->setValue($request, self::$dbClass->subcategory['subcategory_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'page');
        $reflection->setValue($request, 2);
        
        $service = new ProductsCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(ProductsCollection::class, $result);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * Тестирует метод ProductsCollectionService::handle
     * category === null
     * subcategory === null
     * page === null
     * filters === true
     */
    public function testHandleFilters()
    {
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
                return null;
            }
        };
        
        $service = new ProductsCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(ProductsCollection::class, $result);
        $this->assertFalse($result->isEmpty());

        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
