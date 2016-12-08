<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorsSphinxFilterFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\ColorsModel;

class ColorsSphinxFilterFinderTests extends TestCase
{
    /**
     * Тестирует свойства ColorsSphinxFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsSphinxFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('found'));
    }
    
    /**
     * Тестирует метод ColorsSphinxFilterFinder::rules
     */
    public function testRules()
    {
        $finder = new ColorsSphinxFilterFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertArrayHasKey('found', $finder->errors);
        
        $finder = new ColorsSphinxFilterFinder();
        $finder->attributes = ['found'=>'found'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод ColorsSphinxFilterFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new ColorsSphinxFilterFinder();
        
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
        $this->assertSame(ColorsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products` ON `products_colors`.`id_product`=`products`.`id` WHERE (`products`.`active`=TRUE) AND (`products`.`id` IN (1, 2, 3))";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
