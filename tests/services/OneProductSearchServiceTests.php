<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\services\OneProductSearchService;
use app\repository\RepositoryInterface;
use app\models\{AbstractBaseCollection,
    ProductsModel};

class OneProductSearchServiceTests extends TestCase
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
     * Тестирует метод OneProductSearchService::setRepository
     * передаю не поддерживающий интерфейс RepositoryInterface объект
     * @expectedException TypeError
     */
    public function testSetRepositoryError()
    {
        $service = new OneProductSearchService();
        $service->repository = new class () {};
    }
    
    /**
     * Тестирует метод OneProductSearchService::search
     * вызываю с пустым параметром $request
     * @expectedException yii\base\ErrorException
     */
    public function testSearchError()
    {
        $service = new OneProductSearchService();
        $service->search('');
    }
    
    /**
     * Тестирует метод OneProductSearchService::search
     * вызываю с пустым OneProductSearchService::repository
     * @expectedException yii\base\ErrorException
     */
    public function testSearchErrorRepository()
    {
        $service = new OneProductSearchService();
        $service->search([\Yii::$app->params['productKey']=>'some']);
    }
    
    /**
     * Тестирует метод OneProductSearchService::search
     */
    public function testGetGroup()
    {
        $fixture = self::$dbClass->products['product_1'];
        
        $service = new OneProductSearchService();
        //$service->repository = 
        $service->search([\Yii::$app->params['productKey']=>$fixture['seocode']]);
    }
    
    /**
     * Тестирует метод OneProductSearchService::getGroup
     * при отсутствии данных, удовлетворяющих условиям запроса
     */
    /*public function testGetGroupNull()
    {
        $repository = new OneProductSearchService();
        $repository->class = CurrencyModel::class;
        $repository->items = new class () extends AbstractBaseCollection implements RepositoryInterface {
            public $items = [];
            public function add($model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $result = $repository->getGroup(self::$wrongKey);
        
        $this->assertNull($result);
    }*/
    
    /**
     * Тестирует метод OneProductSearchService::getOne
     */
    /*public function testGetOne()
    {
        $fixture = self::$dbClass->brands['brand_1'];
        self::$session->open();
        self::$session->set(self::$key, $fixture);
        self::$session->close();
        
        $repository = new OneProductSearchService();
        $repository->class = BrandsModel::class;
        $result = $repository->getOne(self::$key);
        
        $this->assertTrue($result instanceof BrandsModel);
    }*/
    
    /**
     * Тестирует метод OneProductSearchService::getOne
     * при отсутствии данных, удовлетворяющих условиям запроса
     */
    /*public function testgetOneNull()
    {
        $repository = new OneProductSearchService();
        $repository->class = AddressModel::class;
        $result = $repository->getOne(self::$wrongKey);
        
        $this->assertNull($result);
    }*/
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
