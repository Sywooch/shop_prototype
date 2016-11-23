<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use yii\db\ActiveRecord;
use app\repositories\DbRepository;
use app\models\{AbstractBaseCollection,
    CollectionInterface,
    CriteriaInterface};

class DbRepositoryTests extends TestCase
{
    private $mockModel;
    private $mockCollection;
    
    public static function setUpBeforeClass()
    {
        \Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS {{testTable}} ([[id]] INT AUTO_INCREMENT PRIMARY KEY, [[name]] VARCHAR(255), [[short_description]] VARCHAR(500))')->execute();
        \Yii::$app->db->createCommand('INSERT INTO {{testTable}} (id,name,short_description) VALUES (1,\'one\',\'one\'), (2,\'two\',\'two\'), (3,\'three\',\'three\')')->execute();
    }
    
    public function setUp()
    {
        $this->mockModel = new class() extends ActiveRecord {
            public static function tableName()
            {
                return 'testTable';
            }
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
            public function getByKey(string $key, $value)
            {
            }
            public function update(string $key, $model)
            {
            }
            public function getArray()
            {
            }
        };
    }
    
    /**
     * Тестирует метод DbRepository::setItems
     * передаю не поддерживающий CollectionInterface объект
     * @expectedException TypeError
     */
    public function testSetItemsError()
    {
        $repository = new DbRepository();
        $repository->items = new class () {};
    }
    
    /**
     * Тестирует метод DbRepository::getCriteria
     */
    public function testGetCriteria()
    {
        $repository = new DbRepository();
        $result = $repository->getCriteria();
        
        $this->assertTrue($result instanceof CriteriaInterface);
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
        $repository->class = $this->mockModel ::className();
        $repository->items = $this->mockCollection;
        
        $result = $repository->getGroup();
        
        $this->assertTrue($result instanceof CollectionInterface);
        
        $count = 0;
        foreach ($result as $object) {
            $this->assertTrue($object instanceof $this->mockModel);
            ++$count;
        }
        $this->assertEquals(3, $count);
    }
    
    /**
     * Тестирует метод DbRepository::getGroup
     * с применением критериев выборки
     */
    public function testGetGroupCriteria()
    {
        $repository = new DbRepository();
        $repository->class = $this->mockModel::className();
        $repository->items = $this->mockCollection;
        $criteria = $repository->getCriteria();
        $criteria->where(['!=', '[[name]]', 'three']);
        $result = $repository->getGroup();
        
        $this->assertTrue($result instanceof CollectionInterface);
        
        $count = 0;
        foreach ($result as $object) {
            $this->assertTrue($object instanceof $this->mockModel);
            ++$count;
        }
        $this->assertEquals(2, $count);
    }
    
    /**
     * Тестирует метод DbRepository::getGroup
     * при отсутствии данных, удовлетворяющих условиям SQL запроса
     */
    public function testGetGroupCriteriaNull()
    {
        $repository = new DbRepository();
        $repository->class = $this->mockModel::className();
        $repository->items = $this->mockCollection;
        $criteria = $repository->getCriteria();
        $criteria->where(['in', '[[id]]', [234, 500]]);
        $result = $repository->getGroup();
        
        $this->assertTrue($result->isEmpty());
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     */
    public function testGetOne()
    {
        $repository = new DbRepository();
        $repository->class = $this->mockModel::className();
        $result = $repository->getOne();
        
        $this->assertTrue($result instanceof $this->mockModel);
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     * с применением критериев выборки
     */
    public function testGetOneCriteria()
    {
        $repository = new DbRepository();
        $repository->class = $this->mockModel::className();
        $criteria = $repository->getCriteria();
        $criteria->where(['[[name]]'=>'three']);
        $result = $repository->getOne();
        
        $this->assertTrue($result instanceof $this->mockModel);
    }
    
    /**
     * Тестирует метод DbRepository::getOne
     * при отсутствии данных, удовлетворяющих условиям SQL запроса
     */
    public function testgetOneCriteriaNull()
    {
        $repository = new DbRepository();
        $repository->class = $this->mockModel::className();
        $criteria = $repository->getCriteria();
        $criteria->where(['[[name]]'=>'hundred']);
        $result = $repository->getOne();
        
        $this->assertNull($result);
    }
    
    public static function tearDownAfterClass()
    {
        \Yii::$app->db->createCommand('DROP TABLE {{testTable}}')->execute();
    }
}
