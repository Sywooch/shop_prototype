<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\LightPagination;
use yii\db\Query;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

class LightPaginationTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод LightPagination::setTotalCount
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTotalCountError()
    {
        $query = new class() {};
        $pagination = new LightPagination();
        $pagination->setTotalCount($query);
    }
    
    /**
     * Тестирует метод LightPagination::setTotalCount
     */
    public function testSetTotalCount()
    {
        $data = \Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryAll();
        
        $query = new Query();
        $query->from('{{products}}');
        
        $pagination = new LightPagination();
        $pagination->setTotalCount($query);
        
        $reflection = new \ReflectionProperty($pagination, 'totalCount');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($pagination);
        
        $this->assertSame(count($data), $result);
    }
    
    /**
     * Тестирует метод LightPagination::getTotalCount
     */
    public function testGetTotalCount()
    {
        
        $pagination = new LightPagination();
        
        $result = $pagination->getTotalCount();
        
        $this->assertSame(0, $result);
        
        $reflection = new \ReflectionProperty($pagination, 'totalCount');
        $reflection->setAccessible(true);
        $reflection->setValue($pagination, 75);
        
        $result = $pagination->getTotalCount();
        
        $this->assertSame(75, $result);
    }
    
    /**
     * Тестирует метод LightPagination::getPageCount
     */
    public function testGetPageCount()
    {
        $pagination = new LightPagination();
        
        $totalCount = new \ReflectionProperty($pagination, 'totalCount');
        $totalCount->setAccessible(true);
        $totalCount->setValue($pagination, 29);
        
        $pageSize = new \ReflectionProperty($pagination, 'pageSize');
        $pageSize->setAccessible(true);
        $pageSize->setValue($pagination, 10);
        
        $result = $pagination->getPageCount();
        
        $this->assertSame((int) ceil(29 / 10), $result);
    }
    
    /**
     * Тестирует метод LightPagination::getOffset
     */
    public function testGetOffset()
    {
        $pagination = new LightPagination();
        
        $pageSize = new \ReflectionProperty($pagination, 'pageSize');
        $pageSize->setAccessible(true);
        $pageSize->setValue($pagination, 8);
        
        $page = new \ReflectionProperty($pagination, 'page');
        $page->setAccessible(true);
        $page->setValue($pagination, 16);
        
        $result = $pagination->getOffset();
        
        $this->assertSame(8 * 16, $result);
        
        $pageSize = new \ReflectionProperty($pagination, 'pageSize');
        $pageSize->setAccessible(true);
        $pageSize->setValue($pagination, -1);
        
        $result = $pagination->getOffset();
        
        $this->assertSame(0, $result);
    }
    
    /**
     * Тестирует метод LightPagination::getLimit
     */
    public function testGetLimit()
    {
        $pagination = new LightPagination();
        
        $pageSize = new \ReflectionProperty($pagination, 'pageSize');
        $pageSize->setAccessible(true);
        $pageSize->setValue($pagination, 8);
        
        $result = $pagination->getLimit();
        
        $this->assertSame(8, $result);
        
        $pageSize = new \ReflectionProperty($pagination, 'pageSize');
        $pageSize->setAccessible(true);
        $pageSize->setValue($pagination, -1);
        
        $result = $pagination->getLimit();
        
        $this->assertSame(1, $result);
    }
    
    /**
     * Тестирует метод LightPagination::setPageSize
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPageSizeError()
    {
        $pagination = new LightPagination();
        $pagination->setPageSize('');
    }
    
    /**
     * Тестирует метод LightPagination::setPageSize
     */
    public function testSetPageSize()
    {
        $pagination = new LightPagination();
        $pagination->setPageSize(14);
        
        $property = new \ReflectionProperty($pagination, 'pageSize');
        $property->setAccessible(true);
        $result = $property->getValue($pagination);
        
        $this->assertSame(14, $result);
    }
    
    /**
     * Тестирует метод LightPagination::setPage
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPageError()
    {
        $pagination = new LightPagination();
        $pagination->setPage('');
    }
    
    /**
     * Тестирует метод LightPagination::setPage
     */
    public function testSetPage()
    {
        $pagination = new LightPagination();
        $pagination->setPage(76);
        
        $property = new \ReflectionProperty($pagination, 'page');
        $property->setAccessible(true);
        $result = $property->getValue($pagination);
        
        $this->assertSame(76, $result);
    }
    
    /**
     * Тестирует метод LightPagination::getPage
     */
    public function testGetPage()
    {
        $pagination = new LightPagination();
        
        $property = new \ReflectionProperty($pagination, 'page');
        $property->setAccessible(true);
        $property->setValue($pagination, 34);
        
        $result = $pagination->getPage();
        
        $this->assertSame(34, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
