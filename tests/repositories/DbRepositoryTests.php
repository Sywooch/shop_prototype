<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use yii\db\Query;
use app\tests\DbManager;
use app\repositories\DbRepository;
use app\queries\{CriteriaInterface,
    QueryCriteria};
use app\filters\{FilterInterface,
    WhereFilter};
use app\models\{CollectionInterface,
    ProductsModel};
use app\tests\sources\fixtures\ProductsFixture;

class DbRepositoryTests extends TestCase
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
     * Тестирует метод DbRepository::setCriteria
     * передаю не поддерживающий CriteriaInterface объект
     * @expectedException TypeError
     */
    public function testSetCriteriaError()
    {
        $repository = new DbRepository();
        $repository->criteria = new class() {};
    }
    
    /**
     * Тестирует методы DbRepository::setCriteria, DbRepository::getCriteria
     */
    public function testSetGetCriteria()
    {
        $repository = new DbRepository();
        
        $this->assertNull($repository->criteria);
        
        $criteria = new class() implements CriteriaInterface {
            public function apply($query) {}
            public function setFilter(FilterInterface $filter) {}
        };
        
        $repository = new DbRepository();
        $repository->criteria = $criteria;
        
        $this->assertTrue($repository->criteria instanceof CriteriaInterface);
    }
    
    /**
     * Тестирует метод DbRepository::setCollection
     * передаю не поддерживающий CollectionInterface объект
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $repository = new DbRepository();
        $repository->collection = new class() {};
    }
    
    /**
     * Тестирует методы DbRepository::setCollection, DbRepository::getCollection
     */
    public function testSetGetCollection()
    {
        $repository = new DbRepository();
        
        $this->assertNull($repository->collection);
        
        $collection = new class() implements CollectionInterface {
            public function add(Model $entity) {}
            public function isEmpty() {}
            public function getArray() {}
            public function hasEntity(Model $object) {}
            public function update(Model $object) {}
        };
        
        $repository = new DbRepository();
        $repository->collection = $collection;
        
        $this->assertTrue($repository->collection instanceof CollectionInterface);
    }
    
    /**
     * Тестирует метод DbRepository::setQuery
     * передаю не наследующий Query объект
     * @expectedException TypeError
     */
    public function testSetQueryError()
    {
        $repository = new DbRepository();
        $repository->query = new class() {};
    }
    
    /**
     * Тестирует методы DbRepository::setQuery, DbRepository::getQuery
     */
    public function testSetGetQuery()
    {
        $repository = new DbRepository();
        
        $this->assertNull($repository->query);
        
        $query = new class() extends Query {};
        
        $repository = new DbRepository();
        $repository->query = $query;
        
        $this->assertTrue($repository->query instanceof Query);
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     */
    public function testGetOne()
    {
        $fixture = self::$dbClass->products['product_1'];
        
        $repository = new DbRepository();
        $repository->query = ProductsModel::find();
        $repository->criteria = new QueryCriteria();
        
        $criteria = $repository->criteria;
        $criteria->setFilter(new WhereFilter(['condition'=>['[[seocode]]'=>$fixture['seocode']]]));
        $result = $repository->getOne();
        
        $this->assertTrue($result instanceof ProductsModel);
        $this->assertEquals($fixture['id'], $result->id);
        $this->assertEquals($fixture['seocode'], $result->seocode);
        $this->assertEquals($fixture['name'], $result->name);
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     * при отсутствии данных
     */
    public function testGetOneNull()
    {
        $repository = new DbRepository();
        $repository->query = ProductsModel::find();
        $repository->criteria = new QueryCriteria();
        
        $criteria = $repository->criteria;
        $criteria->setFilter(new WhereFilter(['condition'=>['[[seocode]]'=>'not data']]));
        $result = $repository->getOne();
        
        $this->assertNull($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
