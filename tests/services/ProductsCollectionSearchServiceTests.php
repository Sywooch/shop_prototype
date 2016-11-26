<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\services\ProductsCollectionSearchService;
use app\repositories\DbRepository;
use app\queries\{LightPagination,
    PaginationInterface,
    QueryCriteria};
use app\tests\sources\fixtures\ProductsFixture;
use app\models\{CollectionInterface,
    ProductsCollection,
    ProductsModel};

class ProductsCollectionSearchServiceTests extends TestCase
{
    private static $dbClass;
    private $repository;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->repository = new DbRepository([
            'query'=>ProductsModel::find(),
            'collection'=>new ProductsCollection([
                'pagination'=>new LightPagination()
            ]),
            'criteria'=>new QueryCriteria()
        ]);
    }
    
    /**
     * Тестирует метод ProductsCollectionSearchService::init
     * вызываю с пустым $repository
     * @expectedException yii\base\ErrorException
     */
    public function testSetRepositoryEmpty()
    {
        $service = new ProductsCollectionSearchService();
    }
    
    /**
     * Тестирует метод ProductsCollectionSearchService::setRepository
     * передаю не поддерживающий RepositoryInterface объект
     * @expectedException TypeError
     */
    public function testSetRepositoryError()
    {
        $service = new ProductsCollectionSearchService([
            'repository'=>new class () {}
        ]);
    }
    
    /**
     * Тестирует метод ProductsCollectionSearchService::search
     */
    public function testSearch()
    {
        $request = [\Yii::$app->params['productKey']=>self::$dbClass->products['product_1']['seocode']];
        
        $service = new ProductsCollectionSearchService([
            'repository'=>$this->repository
        ]);
        
        $result = $service->search($request);
        
        $this->assertTrue($result instanceof CollectionInterface);
        foreach ($result as $object) {
            $this->assertTrue($object instanceof ProductsModel);
        }
        $this->assertTrue($result->pagination instanceof PaginationInterface);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
