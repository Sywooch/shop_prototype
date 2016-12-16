<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\{LightPagination,
    PaginationInterface,
    ProductsCollection};

/**
 * Тестирует трейт ProductsCollection
 */
class ProductsCollectionTests extends TestCase
{
    /**
     * Тестирует свойства ProductsCollection
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsCollection::class);
        
        $this->assertTrue($reflection->hasProperty('pagination'));
    }
    
    /**
     * Тестирует метод ProductsCollection::setPagination
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPaginationError()
    {
        $pagination = new class() {};
        
        $collection = new ProductsCollection();
        $collection->setPagination($pagination);
    }
    
    /**
     * Тестирует метод ProductsCollection::setPagination
     */
    public function testSetPagination()
    {
        $pagination = new class() extends LightPagination {};
        
        $collection = new ProductsCollection();
        $collection->setPagination($pagination);
        
        $reflection = new \ReflectionProperty($collection, 'pagination');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(PaginationInterface::class, $result);
    }
    
    /**
     * Тестирует метод ProductsCollection::getPagination
     */
    public function testGetPagination()
    {
        $pagination = new class() extends LightPagination {};
        
        $collection = new ProductsCollection();
        
        $reflection = new \ReflectionProperty($collection, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $pagination);
        
        $result = $collection->getPagination();
        
        $this->assertInstanceOf(PaginationInterface::class, $result);
    }
}
