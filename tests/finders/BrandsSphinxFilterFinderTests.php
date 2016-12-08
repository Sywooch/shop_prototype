<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\BrandsSphinxFilterFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\BrandsModel;

class BrandsSphinxFilterFinderTests extends TestCase
{
    /**
     * Тестирует свойства BrandsSphinxFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BrandsSphinxFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('found'));
    }
    
    /**
     * Тестирует метод BrandsSphinxFilterFinder::rules
     */
    public function testRules()
    {
        $finder = new BrandsSphinxFilterFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertArrayHasKey('found', $finder->errors);
        
        $finder = new BrandsSphinxFilterFinder();
        $finder->attributes = ['found'=>'found'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод BrandsSphinxFilterFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new BrandsSphinxFilterFinder();
        
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
        $this->assertSame(BrandsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `brands`.`id`, `brands`.`brand` FROM `brands` INNER JOIN `products` ON `products`.`id_brand`=`brands`.`id` WHERE (`products`.`active`=TRUE) AND (`products`.`id` IN (1, 2, 3))";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
