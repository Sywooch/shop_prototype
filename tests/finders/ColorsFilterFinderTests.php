<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorsFilterFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\ColorsModel;

class ColorsFilterFinderTests extends TestCase
{
    /**
     * Тестирует свойства ColorsFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::rules
     */
    public function testRules()
    {
        $finder = new ColorsFilterFinder();
        $finder->attributes = [
            'category'=>'category',
            'subcategory'=>'subcategory',
        ];
        
        $this->assertSame('category', $finder->category);
        $this->assertSame('subcategory', $finder->subcategory);
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new ColorsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(ColorsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products` ON `products_colors`.`id_product`=`products`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::find
     * если ColorsFilterFinder::category не пустое
     */
    public function testFindCategory()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new ColorsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'category');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'shoes');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(ColorsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products` ON `products_colors`.`id_product`=`products`.`id` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` WHERE (`products`.`active`=TRUE) AND (`categories`.`seocode`='shoes')";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::find
     * если ColorsFilterFinder::subcategory не пустое
     */
    public function testFindSubcategory()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new ColorsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'category');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'shoes');
        
        $reflection = new \ReflectionProperty($finder, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'sneakers');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(ColorsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products` ON `products_colors`.`id_product`=`products`.`id` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` WHERE ((`products`.`active`=TRUE) AND (`categories`.`seocode`='shoes')) AND (`subcategory`.`seocode`='sneakers')";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
