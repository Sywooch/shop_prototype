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
     * Тестирует метод AdminProductsCollectionService::get
     * page === null
     * filters === null
     */
    public function testGet()
    {
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
        
        $service = new AdminProductsCollectionService();
        $result = $service->get();

        $this->assertInstanceOf(ProductsCollection::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::get
     * page === true
     * filters === null
     */
    public function testGetPage()
    {
        $_GET = [\Yii::$app->params['pagePointer']=>2];
        
        $service = new AdminProductsCollectionService();
        $result = $service->get();

        $this->assertInstanceOf(ProductsCollection::class, $result);
    }

    /**
     * Тестирует метод AdminProductsCollectionService::get
     * page === null
     * filters === true
     */
    public function testGetFilters()
    {
        $_GET = [];
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

        $service = new AdminProductsCollectionService();
        $result = $service->get();

        $this->assertInstanceOf(ProductsCollection::class, $result);

        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
