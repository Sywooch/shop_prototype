<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AdminProductsCollectionService;
use app\tests\sources\fixtures\ProductsFixture;
use app\tests\DbManager;
use app\controllers\AdminController;
use app\collections\ProductsCollection;
use app\helpers\HashHelper;
use yii\helpers\Url;

/**
 * Тестирует класс AdminProductsCollectionService
 */
class AdminProductsCollectionServiceTests extends TestCase
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
     * Тестирует свойства AdminProductsCollectionService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsCollectionService::class);
        
        $this->assertTrue($reflection->hasProperty('productsCollection'));
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AdminProductsCollectionService();
        $service->handle();
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::handle
     * page === null
     * filters === null
     */
    public function testHandle()
    {
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new AdminProductsCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(ProductsCollection::class, $result);
        $this->assertFalse($result->isEmpty());
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::handle
     * page === true
     * filters === null
     */
    public function testHandlePage()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 2;
            }
        };
        
        $service = new AdminProductsCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(ProductsCollection::class, $result);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * Тестирует метод AdminProductsCollectionService::handle
     * page === null
     * filters === true
     */
    public function testHandleFilters()
    {
        $key = HashHelper::createFiltersKey(Url::current());
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'sortingField'=>'views',
            'sortingType'=>SORT_ASC,
            'colors'=>[1, 2, 3, 4, 5],
            'sizes'=>[1, 2, 3, 4, 5],
            'brands'=>[1, 2, 3, 4, 5],
            'categories'=>[1, 2],
            'subcategory'=>[1, 2],
            'active'=>true
        ]);

        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new AdminProductsCollectionService();
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
