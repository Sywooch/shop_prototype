<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SizesSphinxFilterFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\SizesModel;

class SizesSphinxFilterFinderTests extends TestCase
{
    /**
     * Тестирует свойства SizesSphinxFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizesSphinxFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('found'));
    }
    
    /**
     * Тестирует метод SizesSphinxFilterFinder::rules
     */
    public function testRules()
    {
        $finder = new SizesSphinxFilterFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertArrayHasKey('found', $finder->errors);
        
        $finder = new SizesSphinxFilterFinder();
        $finder->attributes = ['found'=>'found'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод SizesSphinxFilterFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new SizesSphinxFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'found');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, [1, 2, 3]);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(SizesModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_size` INNER JOIN `products` ON `products_sizes`.`id_product`=`products`.`id` WHERE (`products`.`active`=TRUE) AND (`products`.`id` IN (1, 2, 3))";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
