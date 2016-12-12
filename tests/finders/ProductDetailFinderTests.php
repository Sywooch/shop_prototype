<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductDetailFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\ProductsModel;

class ProductDetailFinderTests extends TestCase
{
    /**
     * Тестирует свойства ProductDetailFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductDetailFinder::class);
        
        $this->assertTrue($reflection->hasProperty('seocode'));
    }
    
    /**
     * Тестирует метод ProductDetailFinder::rules
     */
    public function testRules()
    {
        $finder = new ProductDetailFinder();
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('seocode', $finder->errors);
        
        $finder = new ProductDetailFinder();
        $finder->attributes = ['seocode'=>'seocode'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод ProductDetailFinder::find
     * усли пуст ProductDetailFinder::collection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFindEmptyCollection()
    {
        $finder = new ProductDetailFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductDetailFinder::seocode
     * усли пуст ProductDetailFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: seocode
     */
    public function testFindEmptySeocode()
    {
        $finder = new ProductDetailFinder();
        
        $collection = new class() extends BaseCollection {};
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductDetailFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new ProductDetailFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'seocode');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'seocode');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(ProductsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `products`.`id`, `products`.`code`, `products`.`name`, `products`.`price`, `products`.`description`, `products`.`images`, `products`.`seocode`, `products`.`id_category`, `products`.`id_subcategory` FROM `products` WHERE `products`.`seocode`='seocode'";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
