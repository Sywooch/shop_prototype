<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsSphinxFinder;
use app\collections\{BaseCollection,
    CollectionInterface,
    LightPagination};
use yii\db\Query;
use app\models\ProductsModel;

class ProductsSphinxFinderTests extends TestCase
{
    /**
     * Тестирует свойства ProductsSphinxFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsSphinxFinder::class);
        
        $this->assertTrue($reflection->hasProperty('found'));
        $this->assertTrue($reflection->hasProperty('page'));
    }
    
    /**
     * Тестирует метод ProductsSphinxFinder::rules
     */
    public function testRules()
    {
        $finder = new ProductsSphinxFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertArrayHasKey('found', $finder->errors);
        
        $finder = new ProductsSphinxFinder();
        $finder->attributes = [
            'found'=>'found',
            'page'=>'page'
        ];
        
        $this->assertEmpty($finder->errors);
        $this->assertSame('found', $finder->found);
        $this->assertSame('page', $finder->page);
    }
    
    /**
     * Тестирует метод ProductsSphinxFinder::find
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
        
        $finder = new ProductsSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'found');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, [1, 2, 3, 4, 5]);
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
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
        
        $expectedQuery = "SELECT `products`.`id`, `products`.`name`, `products`.`price`, `products`.`short_description`, `products`.`images`, `products`.`seocode` FROM `products` WHERE (`products`.`active`=TRUE) AND (`products`.`id` IN (1, 2, 3, 4, 5)) ORDER BY `products`.`date` DESC LIMIT 3";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
