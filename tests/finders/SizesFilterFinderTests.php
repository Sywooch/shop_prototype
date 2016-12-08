<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SizesFilterFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\SizesModel;

class SizesFilterFinderTests extends TestCase
{
    /**
     * Тестирует свойства SizesFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizesFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
    }
    
    /**
     * Тестирует метод SizesFilterFinder::rules
     */
    public function testRules()
    {
        $finder = new SizesFilterFinder();
        $finder->attributes = [
            'category'=>'category',
            'subcategory'=>'subcategory',
        ];
        
        $this->assertSame('category', $finder->category);
        $this->assertSame('subcategory', $finder->subcategory);
    }
    
    /**
     * Тестирует метод SizesFilterFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new SizesFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(SizesModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_size` INNER JOIN `products` ON `products_sizes`.`id_product`=`products`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод SizesFilterFinder::find
     * если SizesFilterFinder::category не пустое
     */
    public function testFindCategory()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new SizesFilterFinder();
        
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
        $this->assertSame(SizesModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_size` INNER JOIN `products` ON `products_sizes`.`id_product`=`products`.`id` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` WHERE (`products`.`active`=TRUE) AND (`categories`.`seocode`='shoes')";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод SizesFilterFinder::find
     * если SizesFilterFinder::subcategory не пустое
     */
    public function testFindSubcategory()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new SizesFilterFinder();
        
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
        $this->assertSame(SizesModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_size` INNER JOIN `products` ON `products_sizes`.`id_product`=`products`.`id` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` WHERE ((`products`.`active`=TRUE) AND (`categories`.`seocode`='shoes')) AND (`subcategory`.`seocode`='sneakers')";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
