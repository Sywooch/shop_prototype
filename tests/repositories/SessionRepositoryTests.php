<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\repositories\SessionRepository;
use app\models\{AbstractBaseCollection,
    CollectionInterface};

class SessionRepositoryTests extends TestCase
{
    private $mockModel;
    private $mockCollection;
    private static $session;
    private static $key = 'sessionKey';
    private static $oneKey = 'sessionOneKey';
    private static $wrongKey = 'wrongKey';
    
    public static function setUpBeforeClass()
    {
        self::$session = \Yii::$app->session;
        self::$session->open();
        self::$session->set(self::$key, [['id'=>1, 'one'=>'one', 'two'=>'two'], ['id'=>2, 'one'=>'one', 'two'=>'two'], ['id'=>3, 'one'=>'one', 'two'=>'two']]);
        self::$session->set(self::$oneKey, ['id'=>1, 'one'=>'one', 'two'=>'two']);
        self::$session->close();
    }
    
    public function setUp()
    {
        $this->mockModel = new class() extends Model {
            public $id;
            public $one;
            public $two;
        };
        
        $this->mockCollection = new class () extends AbstractBaseCollection implements CollectionInterface {
            public $items = [];
            public function add(Model $model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
            public function getArray(): array
            {
                $result = [];
                foreach ($this->items as $item) {
                    $result[] = $item->toArray();
                }
                return $result;
            }
            public function hasEntity(Model $object)
            {
            }
            public function update(Model $object)
            {
            }
        };
    }
    
    /**
     * Тестирует метод SessionRepository::setCollection
     * передаю не поддерживающий CollectionInterface объект
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $repository = new SessionRepository();
        $repository->collection = new class () {};
    }
    
    /**
     * Тестирует метод SessionRepository::getGroup
     * вызываю с пустым $collection
     * @expectedException yii\base\ErrorException
     */
    public function testGetGroupCollectionError()
    {
        $repository = new SessionRepository();
        $repository->getGroup();
    }
    
    /**
     * Тестирует метод SessionRepository::getGroup
     */
    public function testGetGroup()
    {
        $repository = new SessionRepository();
        $repository->class = $this->mockModel ::className();
        $repository->collection = $this->mockCollection;
        
        $result = $repository->getGroup(self::$key);
        
        $this->assertTrue($result instanceof CollectionInterface);
        
        $count = 0;
        foreach ($result as $object) {
            $this->assertTrue($object instanceof $this->mockModel);
            ++$count;
        }
        $this->assertEquals(3, $count);
    }
    
    /**
     * Тестирует метод SessionRepository::getGroup
     * при отсутствии данных, удовлетворяющих условиям запроса
     */
    public function testGetGroupEmpty()
    {
        $repository = new SessionRepository();
        $repository->class = $this->mockModel ::className();
        $repository->collection = $this->mockCollection;
        
        $result = $repository->getGroup(self::$wrongKey);
        
        $this->assertTrue($result->isEmpty());
    }
    
    /**
     * Тестирует метод SessionRepository::getOne
     */
    public function testGetOne()
    {
        $repository = new SessionRepository();
        $repository->class = $this->mockModel::className();
        $result = $repository->getOne(self::$oneKey);
        
        $this->assertTrue($result instanceof $this->mockModel);
    }
    
    /**
     * Тестирует метод SessionRepository::getOne
     * при отсутствии данных, удовлетворяющих условиям запроса
     */
    public function testGetOneNull()
    {
        $repository = new SessionRepository();
        $repository->class = $this->mockModel::className();
        $result = $repository->getOne(self::$wrongKey);
        
        $this->assertNull($result);
    }
    
    /**
     * Тестирует метод SessionRepository::saveGroup
     * вызываю с пустым $collection
     * @expectedException yii\base\ErrorException
     */
    public function testSaveGroupCollectionEmpty()
    {
        $repository = new SessionRepository();
        $repository->saveGroup('');
    }
    
    /**
     * Тестирует метод SessionRepository::saveGroup
     */
    public function testSaveGroup()
    {
        $model = new class() extends Model {
            public $data = 3;
        };
        
        $this->mockCollection->add($model);
        $this->mockCollection->add($model);
        
        $repository = new SessionRepository();
        $repository->collection = $this->mockCollection;
        
        self::$session->open();
        self::$session->remove(self::$key);
        
        $this->assertFalse(self::$session->has(self::$key));
        
        $repository->saveGroup(self::$key);
        
        $this->assertTrue(self::$session->has(self::$key));
        
        self::$session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$session->open();
        self::$session->remove(self::$key);
        self::$session->remove(self::$oneKey);
        self::$session->close();
    }
}
