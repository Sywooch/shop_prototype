<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\sources\fixtures\{AddressFixture,
    BrandsFixture,
    CategoriesFixture,
    CurrencyFixture};
use app\repository\SessionRepository;
use app\models\{AbstractBaseCollection,
    AddressModel,
    BrandsModel,
    CollectionInterface,
    CategoriesModel,
    CurrencyModel};

class SessionRepositoryTests extends TestCase
{
    private static $dbClass;
    private static $session;
    private static $key = 'sessionKey';
    private static $wrongKey = 'wrongKey';
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'currency'=>CurrencyFixture::class,
                'brands'=>BrandsFixture::class,
                'address'=>AddressFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        self::$session = \Yii::$app->session;
    }
    
    /**
     * Тестирует метод SessionRepository::setItems
     * передаю не поддерживающий интерфейс CollectionInterface объект
     * @expectedException TypeError
     */
    public function testSetItemsError()
    {
        $repository = new SessionRepository();
        $repository->items = new class () {};
    }
    
    /**
     * Тестирует метод SessionRepository::getGroup
     * вызываю с пустым SessionRepository::items
     * @expectedException yii\base\ErrorException
     */
    public function testGetGroupError()
    {
        $repository = new SessionRepository();
        $repository->getGroup();
    }
    
    /**
     * Тестирует метод SessionRepository::getGroup
     */
    public function testGetGroup()
    {
        $fixture = self::$dbClass->categories['category_1'];
        $fixture2 = self::$dbClass->categories['category_2'];
        self::$session->open();
        self::$session->set(self::$key, [$fixture, $fixture2]);
        self::$session->close();
        
        $repository = new SessionRepository();
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
        $result = $repository->getGroup(self::$key);
        
        $this->assertTrue($result instanceof CollectionInterface);
        foreach ($result as $object) {
            $this->assertTrue($object instanceof CategoriesModel);
        }
    }
    
    /**
     * Тестирует метод SessionRepository::getGroup
     * при отсутствии данных, удовлетворяющих условиям запроса
     */
    public function testGetGroupNull()
    {
        $repository = new SessionRepository();
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
        $result = $repository->getGroup(self::$wrongKey);
        
        $this->assertNull($result);
    }
    
    /**
     * Тестирует метод SessionRepository::getOne
     */
    public function testGetOne()
    {
        $fixture = self::$dbClass->brands['brand_1'];
        self::$session->open();
        self::$session->set(self::$key, $fixture);
        self::$session->close();
        
        $repository = new SessionRepository();
        $repository->class = BrandsModel::class;
        $result = $repository->getOne(self::$key);
        
        $this->assertTrue($result instanceof BrandsModel);
    }
    
    /**
     * Тестирует метод SessionRepository::getOne
     * при отсутствии данных, удовлетворяющих условиям запроса
     */
    public function testgetOneNull()
    {
        $repository = new SessionRepository();
        $repository->class = AddressModel::class;
        $result = $repository->getOne(self::$wrongKey);
        
        $this->assertNull($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$session->open();
        self::$session->remove(self::$key);
        self::$session->close();
        
        self::$dbClass->unloadFixtures();
    }
}
