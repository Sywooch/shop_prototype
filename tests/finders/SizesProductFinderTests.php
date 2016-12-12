<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SizesProductFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\SizesModel;

/**
 * Тестирует класс SizesProductFinder
 */
class SizesProductFinderTests extends TestCase
{
    /**
     * Тестирует свойства SizesProductFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizesProductFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_product'));
    }
    
    /**
     * Тестирует метод SizesProductFinder::rules
     */
    public function testRules()
    {
        $finder = new SizesProductFinder();
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('id_product', $finder->errors);
        
        $finder = new SizesProductFinder();
        $finder->attributes = ['id_product'=>2];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод SizesProductFinder::find
     * если пуст SizesProductFinder::collection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFindEmptyCollection()
    {
        $finder = new SizesProductFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод SizesProductFinder::find
     * если пуст SizesProductFinder::id_product
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Id Product».
     */
    public function testFindValidationError()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new SizesProductFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $finder->find();
    }
    
    /**
     * Тестирует метод SizesProductFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new SizesProductFinder();
        
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
        $this->assertSame(SizesModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_size` WHERE `products_sizes`.`id_product`=1";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
