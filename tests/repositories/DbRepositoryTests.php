<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use yii\db\Query;
use app\tests\DbManager;
use app\repositories\DbRepository;
use app\queries\QueryCriteria;
use app\filters\WhereFilter;
use app\models\{CollectionInterface,
    ProductsCollection,
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
     * Тестирует метод DbRepository::setCollection
     */
    public function testSetCollection()
    {
        $repository = new DbRepository();
        $repository->collection = new class() implements CollectionInterface {
            public function add(Model $entity) {}
            public function isEmpty() {}
            public function getArray() {}
            public function hasEntity(Model $object) {}
            public function update(Model $object) {}
        };
    }
    
    /**
     * Тестирует методы DbRepository::getCollection
     */
    public function testGetCollection()
    {
        $repository = new DbRepository();
        
        $this->assertNull($repository->collection);
        
        $repository = new DbRepository();
        $repository->collection = new class() implements CollectionInterface {
            public function add(Model $entity) {}
            public function isEmpty() {}
            public function getArray() {}
            public function hasEntity(Model $object) {}
            public function update(Model $object) {}
        };
        
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
     * Тестирует методы DbRepository::setQuery
     */
    public function testSetQuery()
    {
        $repository = new DbRepository();
        $repository->query = new class() extends Query {};
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     * вызываю с пустым $query
     * @expectedException yii\base\ErrorException
     */
    public function testGetOneEmptyQuery()
    {
        $repository = new DbRepository();
        $repository->getOne();
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     */
    public function testGetOne()
    {
        $fixture = self::$dbClass->products['product_1'];
        
        $query = ProductsModel::find();
        
        $criteria = new QueryCriteria();
        $criteria->setFilter(new WhereFilter(['condition'=>['[[seocode]]'=>$fixture['seocode']]]));
        $criteria->apply($query);
        
        $repository = new DbRepository();
        $repository->query = $query;
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
        $query = ProductsModel::find();
        
        $criteria = new QueryCriteria();
        $criteria->setFilter(new WhereFilter(['condition'=>['[[seocode]]'=>'not data']]));
        $criteria->apply($query);
        
        $repository = new DbRepository();
        $repository->query = $query;
        $result = $repository->getOne();
        
        $this->assertNull($result);
    }
    
    /**
     * Тестирует метод DbRepository::getGroup
     * вызываю с пустым $query
     * @expectedException yii\base\ErrorException
     */
    public function testGetGroupEmptyQuery()
    {
        $repository = new DbRepository();
        $repository->getGroup();
    }
    
    /**
     * Тестирует метод DbRepository::getGroup
     * вызываю с пустым $collection
     * @expectedException yii\base\ErrorException
     */
    public function testGetGroupEmptyCollection()
    {
        $repository = new DbRepository();
        $repository->query = new class() extends Query {};
        $repository->getGroup();
    }
    
    /**
     * Тестирует метод DbRepository::getGroup
     */
    public function testGetGroup()
    {
        $fixture = self::$dbClass->products['product_1'];
        
        $query = ProductsModel::find();
        
        $criteria = new QueryCriteria();
        $criteria->setFilter(new WhereFilter(['condition'=>['>', '[[price]]', 200]]));
        $criteria->apply($query);
        
        $collection = new ProductsCollection();
        
        $repository = new DbRepository();
        $repository->query = $query;
        $repository->collection = $collection;
        $result = $repository->getGroup();
        
        $this->assertTrue($result instanceof CollectionInterface);
        foreach ($result as $item) {
            $this->assertTrue($item instanceof ProductsModel);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
