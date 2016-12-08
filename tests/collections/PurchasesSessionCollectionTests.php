<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\PurchasesSessionCollection;
use yii\base\Model;

/**
 * Тестирует класс PurchasesSessionCollection
 */
class PurchasesSessionCollectionTests extends TestCase
{
    /**
     * Тестирует метод PurchasesSessionCollection::hasEntity
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testHasEntityError()
    {
        $model = new class() {};
        $collection = new PurchasesSessionCollection();
        $collection->hasEntity($model);
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::hasEntity
     * если PurchasesSessionCollection::items пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: items
     */
    public function testHasEntityEmpty()
    {
        $model = new class() extends Model {};
        $collection = new PurchasesSessionCollection();
        $collection->hasEntity($model);
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::hasEntity
     * если PurchasesSessionCollection::items содержит массивы
     */
    public function testHasEntityArrays()
    {
        $array_1 = ['id_product'=>1, 'id_color'=>1, 'id_size'=>1];
        $array_2 = ['id_product'=>2, 'id_color'=>2, 'id_size'=>2];
        $array_3 = ['id_product'=>3, 'id_color'=>3, 'id_size'=>3];
        
        $model_1 = new class() extends Model {
            public $id_product = 1;
            public $id_color = 1;
            public $id_size = 1;
        };
        $model_2 = new class() extends Model {
            public $id_product = 2;
            public $id_color = 2;
            public $id_size = 2;
        };
        $model_3 = new class() extends Model {
            public $id_product = 3;
            public $id_color = 3;
            public $id_size = 3;
        };
        $model_3_2 = new class() extends Model {
            public $id_product = 3;
            public $id_color = 2;
            public $id_size = 3;
        };
        
        $collection = new PurchasesSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_2, $array_3]);
        
        $result = $collection->hasEntity($model_1);
        
        $this->assertFalse($result);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_1, $array_2, $array_3]);
        
        $result = $collection->hasEntity($model_2);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_1);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_3);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_3_2);
        $this->assertFalse($result);
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::hasEntity
     * если PurchasesSessionCollection::items содержит объекты
     */
    public function testHasEntityObjects()
    {
        $model_1 = new class() extends Model {
            public $id_product = 1;
            public $id_color = 1;
            public $id_size = 1;
        };
        $model_2 = new class() extends Model {
            public $id_product = 2;
            public $id_color = 2;
            public $id_size = 2;
        };
        $model_3 = new class() extends Model {
            public $id_product = 3;
            public $id_color = 3;
            public $id_size = 3;
        };
        $model_3_2 = new class() extends Model {
            public $id_product = 3;
            public $id_color = 2;
            public $id_size = 3;
        };
        
        $collection = new PurchasesSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2]);
        
        $result = $collection->hasEntity($model_3);
        
        $this->assertFalse($result);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2, $model_3]);
        
        $result = $collection->hasEntity($model_2);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_1);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_3);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_3_2);
        $this->assertFalse($result);
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::update
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testUpdateError()
    {
        $model = new class() {};
        $collection = new PurchasesSessionCollection();
        $collection->update($model);
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::update
     * если PurchasesSessionCollection::items пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: items
     */
    public function testUpdateEmpty()
    {
        $model = new class() extends Model {};
        $collection = new PurchasesSessionCollection();
        $collection->update($model);
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::update
     * если PurchasesSessionCollection::items содержит объекты
     */
    public function testUpdateObjects()
    {
        $model_1 = new class() extends Model {
            public $id_product = 1;
            public $quantity = 2;
        };
        $model_2 = new class() extends Model {
            public $id_product = 2;
            public $quantity = 1;
        };
        $model_3 = new class() extends Model {
            public $id_product = 3;
            public $quantity = 7;
        };
        $model_3_2 = new class() extends Model {
            public $id_product = 3;
            public $quantity = 11;
        };
        
        $collection = new PurchasesSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2, $model_3]);
        
        $collection->update($model_3_2);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        foreach ($result as $element) {
            if ($element->id_product === 3) {
                $this->assertSame(18, $element->quantity);
            }
        }
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::update
     * если PurchasesSessionCollection::items содержит массивы
     */
    public function testUpdateArrays()
    {
        $array_1 = ['id_product'=>1, 'quantity'=>11];
        $array_2 = ['id_product'=>2, 'quantity'=>62];
        $array_3 = ['id_product'=>3, 'quantity'=>31];
        
        $model_3_2 = new class() extends Model {
            public $id_product = 3;
            public $quantity = 11;
        };
        
        $collection = new PurchasesSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_1, $array_2, $array_3]);
        
        $collection->update($model_3_2);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        foreach ($result as $element) {
            if ($element['id_product'] === 3) {
                $this->assertSame(42, $element['quantity']);
            }
        }
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::totalQuantity
     * если PurchasesSessionCollection::items пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: items
     */
    public function testTotalQuantityEmpty()
    {
        $model = new class() extends Model {};
        $collection = new PurchasesSessionCollection();
        $collection->totalQuantity();
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::totalQuantity
     * если PurchasesSessionCollection::items содержит объекты
     */
    public function testTotalQuantityObjects()
    {
        $model_1 = new class() extends Model {
            public $id_product = 1;
            public $quantity = 12;
        };
        $model_2 = new class() extends Model {
            public $id_product = 2;
            public $quantity = 15;
        };
        $model_3 = new class() extends Model {
            public $id_product = 3;
            public $quantity = 7;
        };
        
        $collection = new PurchasesSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2, $model_3]);
        
        $result = $collection->totalQuantity();
        
        $this->assertSame(34, $result);
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::totalQuantity
     * если PurchasesSessionCollection::items содержит массивы
     */
    public function testTotalQuantityArrays()
    {
        $array_1 = ['id_product'=>1, 'quantity'=>11];
        $array_2 = ['id_product'=>2, 'quantity'=>62];
        $array_3 = ['id_product'=>3, 'quantity'=>31];
        
        $collection = new PurchasesSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_1, $array_2, $array_3]);
        
        $result = $collection->totalQuantity();
        
        $this->assertSame(104, $result);
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::totalPrice
     * если PurchasesSessionCollection::items пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: items
     */
    public function testTotalPriceEmpty()
    {
        $model = new class() extends Model {};
        $collection = new PurchasesSessionCollection();
        $collection->totalPrice();
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::totalPrice
     * если PurchasesSessionCollection::items содержит объекты
     */
    public function testTotalPriceObjects()
    {
        $model_1 = new class() extends Model {
            public $id_product = 1;
            public $quantity = 12;
            public $price = 1687.00;
        };
        $model_2 = new class() extends Model {
            public $id_product = 2;
            public $quantity = 15;
            public $price = 16.75;
        };
        $model_3 = new class() extends Model {
            public $id_product = 3;
            public $quantity = 7;
            public $price = 912.34;
        };
        
        $collection = new PurchasesSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2, $model_3]);
        
        $result = $collection->totalPrice();
        
        $this->assertSame(((12 * 1687.00) + (15 * 16.75) + (7 * 912.34)), $result);
    }
    
    /**
     * Тестирует метод PurchasesSessionCollection::totalPrice
     * если PurchasesSessionCollection::items содержит массивы
     */
    public function testTotalPriceArrays()
    {
        $array_1 = ['id_product'=>1, 'quantity'=>11, 'price'=>14.97];
        $array_2 = ['id_product'=>2, 'quantity'=>62, 'price'=>1896.05];
        $array_3 = ['id_product'=>3, 'quantity'=>31, 'price'=>12897.89];
        
        $collection = new PurchasesSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_1, $array_2, $array_3]);
        
        $result = $collection->totalPrice();
        
        $this->assertSame(((11 * 14.97) + (62 * 1896.05) + (31 * 12897.89)), $result);
    }
}
