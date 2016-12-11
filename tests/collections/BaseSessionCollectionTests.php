<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\{BaseSessionCollection,
    SessionCollectionInterface};
use yii\base\Model;

/**
 * Тестирует класс BaseSessionCollection
 */
class BaseSessionCollectionTests extends TestCase
{
    /**
     * Тестирует метод BaseSessionCollection::getModels
     * если BaseSessionCollection::items содержит не массивы
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: items
     */
    public function testGetModelsNotArrays()
    {
        $array = ['id'=>1];
        $model = new class() extends Model {};
        
        $collection = new BaseSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array, $model]);
        
        $collection->getModels($model::className());
    }
    
    /**
     * Тестирует метод BaseSessionCollection::getModels
     */
    public function testGetModels()
    {
        $array_1 = ['id'=>1];
        $array_2 = ['id'=>2];
        
        $model = new class() extends Model {
            const DBMS = 'dbms';
            public $id;
            public function rules()
            {
                return [
                    [['id'], 'safe', 'on'=>self::DBMS],
                ];
            }
        };
        
        $collection = new BaseSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_1, $array_2]);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        foreach ($result as $item) {
            $this->assertInternalType('array', $item);
        }
        
        $result = $collection->getModels($model::className());
        
        $this->assertInstanceOf(SessionCollectionInterface::class, $result);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        foreach ($result as $item) {
            $this->assertInstanceOf($model::className(), $item);
        }
    }
    
    /**
     * Тестирует метод BaseSessionCollection::getModel
     * если BaseSessionCollection::items содержит не массивы
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: items
     */
    public function testGetModelNotArrays()
    {
        $array = ['id'=>1];
        $model = new class() extends Model {};
        
        $collection = new BaseSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array, $model]);
        
        $collection->getModel($model::className());
    }
    
    /**
     * Тестирует метод BaseSessionCollection::getModel
     */
    public function testGetModel()
    {
        $array_1 = ['id'=>1];
        $array_2 = ['id'=>2];
        
        $model = new class() extends Model {
            const DBMS = 'dbms';
            public $id;
            public function rules()
            {
                return [
                    [['id'], 'safe', 'on'=>self::DBMS],
                ];
            }
        };
        
        $collection = new BaseSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_1, $array_2]);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        foreach ($result as $item) {
            $this->assertInternalType('array', $item);
        }
        
        $result = $collection->getModel($model::className());
        
        $this->assertInstanceOf(Model::class, $result);
        $this->assertInstanceOf($model::className(), $result);
    }
    
    /**
     * Тестирует метод BaseSessionCollection::getArray
     * при условии что BaseSessionCollection::items пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: items
     */
    public function testGetArrayEmpty()
    {
        $collection = new BaseSessionCollection();
        $collection->getArray();
    }
    
    /**
     * Тестирует метод BaseSessionCollection::getArray
     * при условии что BaseSessionCollection::items содержит не массивы
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: items
     */
    public function testGetArrayNotArray()
    {
        $model = new class() {};
        
        $collection = new BaseSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model]);
        
        $collection->getArray();
    }
    
    /**
     * Тестирует метод BaseSessionCollection::getArray
     */
    public function testGetArray()
    {
        $array = ['id'=>1, 'one'=>'some one text', 'two'=>23.3467];
        
        $collection = new BaseSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array]);
        
        $result = $collection->getArray();
        
        $this->assertSame($array, $result);
    }
}
