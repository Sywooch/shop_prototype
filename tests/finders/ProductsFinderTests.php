<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsFinder;
use app\collections\{BaseCollection,
    CollectionInterface,
    LightPagination};
use yii\db\Query;
use app\models\ProductsModel;
use app\filters\{ProductsFiltersInterface,
    ProductsFilters};

class ProductsFinderTests extends TestCase
{
    /**
     * Тестирует свойства ProductsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('filters'));
    }
    
    /**
     * Тестирует метод ProductsFinder::rules
     */
    public function testRules()
    {
        $finder = new ProductsFinder();
        $finder->attributes = [
            'category'=>'category',
            'subcategory'=>'subcategory',
            'page'=>'page'
        ];
        
        $this->assertSame('category', $finder->category);
        $this->assertSame('subcategory', $finder->subcategory);
        $this->assertSame('page', $finder->page);
    }
    
    /**
     * Тестирует метод ProductsFinder::setFilters
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFiltersError()
    {
        $filter = new class() {};
        
        $finder = new ProductsFinder();
        
        $finder->setFilters($filter);
    }
    
    /**
     * Тестирует метод ProductsFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsFinder();
        
        $finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(ProductsFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * усли пуст ProductsFinder::collection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFindEmptyCollection()
    {
        $finder = new ProductsFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * усли пуст ProductsFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: filters
     */
    public function testFindEmptyFilters()
    {
        $finder = new ProductsFinder();
        
        $collection = new class() extends BaseCollection {};
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     */
    public function testFind()
    {
        $pagination = new class() extends LightPagination {
            public function getOffset(){
                return 0;
            }
            public function getLimit(){
                return 3;
            }
        };
        
        $collection = new class() extends BaseCollection {};
        
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $reflection = new \ReflectionProperty($collection, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $pagination);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(ProductsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `products`.`id`, `products`.`name`, `products`.`price`, `products`.`short_description`, `products`.`images`, `products`.`seocode` FROM `products` WHERE `products`.`active`=TRUE ORDER BY `products`.`date` DESC LIMIT 3";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
