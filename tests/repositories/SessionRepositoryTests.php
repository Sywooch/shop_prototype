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
            public function add($model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
    }
    
    /**
     * Тестирует метод SessionRepository::setItems
     * передаю не поддерживающий CollectionInterface объект
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
        $repository = new SessionRepository();
        $repository->class = $this->mockModel ::className();
        $repository->items = $this->mockCollection;
        
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
    public function testGetGroupNull()
    {
        $repository = new SessionRepository();
        $repository->class = $this->mockModel ::className();
        $repository->items = $this->mockCollection;
        
        $result = $repository->getGroup(self::$wrongKey);
        
        $this->assertNull($result);
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
    
    public static function tearDownAfterClass()
    {
        self::$session->open();
        self::$session->remove(self::$key);
        self::$session->remove(self::$oneKey);
        self::$session->close();
    }
}
