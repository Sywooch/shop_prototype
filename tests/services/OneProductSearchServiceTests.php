<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\services\OneProductSearchService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\models\{ProductsModel,
    QueryCriteria};

class OneProductSearchServiceTests extends TestCase
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
        $this->repository = new class () extends AbstractBaseRepository implements RepositoryInterface {
            private $criteria;
            
            public function getGroup($request=null)
            {
                
            }
            public function getOne($request=null)
            {
                $query = ProductsModel::find();
                $query = $this->addCriteria($query);
                $data = $query->one();
                return $data;
            }
            public function getCriteria()
            {
                $this->criteria = new QueryCriteria();
                return $this->criteria;
            }
        };
    }
    
    /**
     * Тестирует метод OneProductSearchService::setRepository
     * передаю не поддерживающий RepositoryInterface объект
     * @expectedException TypeError
     */
    public function testSetRepositoryError()
    {
        $service = new OneProductSearchService();
        $service->repository = new class () {};
    }
    
    /**
     * Тестирует метод OneProductSearchService::search
     * вызываю с пустым $request
     * @expectedException yii\base\ErrorException
     */
    public function testSearchRequestError()
    {
        $service = new OneProductSearchService();
        $service->repository = $this->repository;
        $result = $service->search([]);
    }
    
    /**
     * Тестирует метод OneProductSearchService::search
     * вызываю с пустым OneProductSearchService::repository
     * @expectedException yii\base\ErrorException
     */
    public function testSearchRepositoryError()
    {
        $request = [\Yii::$app->params['productKey']=>self::$dbClass->products['product_1']];
        
        $service = new OneProductSearchService();
        $result = $service->search($request);
    }
    
    /**
     * Тестирует метод OneProductSearchService::search
     */
    public function testSearch()
    {
        $request = [\Yii::$app->params['productKey']=>self::$dbClass->products['product_1']];
        
        $service = new OneProductSearchService();
        $service->repository = $this->repository;
        $result = $service->search($request);
        
        $this->assertTrue($result instanceof ProductsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
