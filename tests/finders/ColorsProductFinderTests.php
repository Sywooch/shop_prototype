<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorsProductFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\ColorsModel;

/**
 * Тестирует класс ColorsProductFinder
 */
class ColorsProductFinderTests extends TestCase
{
    /**
     * Тестирует свойства ColorsProductFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsProductFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_product'));
    }
    
    /**
     * Тестирует метод ColorsProductFinder::rules
     */
    public function testRules()
    {
        $finder = new ColorsProductFinder();
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('id_product', $finder->errors);
        
        $finder = new ColorsProductFinder();
        $finder->attributes = ['id_product'=>2];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод ColorsProductFinder::find
     * если пуст ColorsProductFinder::collection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFindEmptyCollection()
    {
        $finder = new ColorsProductFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод ColorsProductFinder::find
     * если пуст ColorsProductFinder::id_product
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Id Product».
     */
    public function testFindValidationError()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new ColorsProductFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $finder->find();
    }
    
    /**
     * Тестирует метод ColorsProductFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new ColorsProductFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'id_product');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(ColorsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_color` WHERE `products_colors`.`id_product`=1";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
