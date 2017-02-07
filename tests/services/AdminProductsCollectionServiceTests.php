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
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('productsCollection'));
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::setKey
     * если передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $service = new AdminProductsCollectionService();
        $service->setKey([]);
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::setKey
     */
    public function testSetKey()
    {
        $service = new AdminProductsCollectionService();
        $service->setKey('key');
        
        $reflection = new \ReflectionProperty($service, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertEquals('key', $result);
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::setPage
     * если передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPageError()
    {
        $service = new AdminProductsCollectionService();
        $service->setPage('a2');
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::setPage
     */
    public function testSetPage()
    {
        $service = new AdminProductsCollectionService();
        $service->setPage(2);
        
        $reflection = new \ReflectionProperty($service, 'page');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertEquals(2, $result);
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::get
     * если пуст AdminProductsCollectionService::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testGetEmptyKey()
    {
        $service = new AdminProductsCollectionService();
        $service->get();
    }
    
    /**
     * Тестирует метод AdminProductsCollectionService::get
     * page === null
     * filters === null
     */
    public function testGet()
    {
        $service = new AdminProductsCollectionService();
        
        $reflection = new \ReflectionProperty($service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($service, HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]));
        
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
        $service = new AdminProductsCollectionService();
        
        $reflection = new \ReflectionProperty($service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($service, HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]));
        
        $reflection = new \ReflectionProperty($service, 'page');
        $reflection->setAccessible(true);
        $reflection->setValue($service, 2);
        
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
        $key = HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]);
        
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
        
        $reflection = new \ReflectionProperty($service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $key);
        
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
