<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\sources\fixtures\{AddressFixture,
    BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    CurrencyFixture};
use app\repository\DbRepository;
use app\models\{AbstractBaseCollection,
    AddressModel,
    BrandsModel,
    CollectionInterface,
    CategoriesModel,
    CurrencyModel,
    ColorsModel,
    QueryCriteria};

class DbRepositoryTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'colors'=>ColorsFixture::class,
                'currency'=>CurrencyFixture::class,
                'brands'=>BrandsFixture::class,
                'address'=>AddressFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод DbRepository::setItems
     * передаю не поддерживающий интерфейс CollectionInterface объект
     * @expectedException TypeError
     */
    public function testSetItemsError()
    {
        $repository = new DbRepository();
        $repository->items = new class () {};
    }
    
    /**
     * Тестирует метод DbRepository::getGroup
     * вызываю с пустым DbRepository::items
     * @expectedException yii\base\ErrorException
     */
    public function testGetGroupError()
    {
        $repository = new DbRepository();
        $repository->getGroup();
    }
    
    /**
     * Тестирует метод DbRepository::getGroup
     */
    public function testGetGroup()
    {
        $repository = new DbRepository();
        $repository->class = CategoriesModel::class;
        $repository->items = new class () extends AbstractBaseCollection implements CollectionInterface {
            public $items = [];
            public function add($model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $result = $repository->getGroup();
        
        $this->assertTrue($result instanceof CollectionInterface);
        foreach ($result as $object) {
            $this->assertTrue($object instanceof CategoriesModel);
        }
    }
    
    /**
     * Тестирует метод DbRepository::getGroup
     * с применением критериев выборки
     */
    public function testGetGroupCriteria()
    {
        $repository = new DbRepository();
        $repository->class = ColorsModel::class;
        $repository->items = new class () extends AbstractBaseCollection implements CollectionInterface {
            public $items = [];
            public function add($model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $criteria = new QueryCriteria();
        $criteria->where(['!=', '[[color]]', 'black']);
        $repository->setCriteria($criteria);
        $result = $repository->getGroup();
        
        $this->assertTrue($result instanceof CollectionInterface);
        foreach ($result as $object) {
            $this->assertTrue($object instanceof ColorsModel);
        }
    }
    
    /**
     * Тестирует метод DbRepository::getGroup
     * при отсутствии данных, удовлетворяющих условиям SQL запроса
     */
    public function testGetGroupCriteriaNull()
    {
        $repository = new DbRepository();
        $repository->class = CurrencyModel::class;
        $repository->items = new class () extends AbstractBaseCollection implements CollectionInterface {
            public $items = [];
            public function add($model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $criteria = new QueryCriteria();
        $criteria->where(['[[id]]'=>[100,230]]);
        $repository->setCriteria($criteria);
        $result = $repository->getGroup();
        
        $this->assertNull($result);
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     */
    public function testGetOne()
    {
        $repository = new DbRepository();
        $repository->class = BrandsModel::class;
        $result = $repository->getOne();
        
        $this->assertTrue($result instanceof BrandsModel);
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     * с применением критериев выборки
     */
    public function testGetOneCriteria()
    {
        $repository = new DbRepository();
        $repository->class = ColorsModel::class;
        $criteria = new QueryCriteria();
        $criteria->where(['[[color]]'=>'red']);
        $repository->setCriteria($criteria);
        $result = $repository->getOne();
        
        $this->assertTrue($result instanceof ColorsModel);
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     * при отсутствии данных, удовлетворяющих условиям SQL запроса
     */
    public function testgetOneCriteriaNull()
    {
        $repository = new DbRepository();
        $repository->class = AddressModel::class;
        $criteria = new QueryCriteria();
        $criteria->where(['[[id]]'=>[899, 453]]);
        $repository->setCriteria($criteria);
        $result = $repository->getOne();
        
        $this->assertNull($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
