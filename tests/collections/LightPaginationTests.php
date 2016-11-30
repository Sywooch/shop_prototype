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
        $pagination->setPageSize(3);
        
        $this->assertEquals(ceil(count($data) / 2), $pagination->getPageCount());
    }
    
    /**
     * Тестирует метод LightPagination::getPageCount
     */
    public function testGetPageCount()
    {
        $data = \Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryAll();
        
        $query = new Query();
        $query->from('{{products}}');
        
        $pagination = new LightPagination();
        $pagination->setTotalCount($query);
        $pagination->setPageSize(3);
        
        $this->assertTrue(is_int($pagination->getPageCount()));
        $this->assertEquals(ceil(count($data) / 3), $pagination->getPageCount());
    }
    
    /**
     * Тестирует метод LightPagination::getOffset
     */
    public function testGetOffset()
    {
        $pagination = new LightPagination();
        $pagination->setPageSize(3);
        $pagination->setPage(2);
        
        $this->assertTrue(is_int($pagination->getOffset()));
        $this->assertEquals(6, $pagination->getOffset());
        
        $pagination = new LightPagination();
        $pagination->setPageSize(-1);
        $pagination->setPage(2);
        
        $this->assertEquals(0, $pagination->getOffset());
    }
    
    /**
     * Тестирует метод LightPagination::getLimit
     */
    public function testGetLimit()
    {
        $pagination = new LightPagination();
        $pagination->setPageSize(3);
        
        $this->assertTrue(is_int($pagination->getLimit()));
        $this->assertEquals(3, $pagination->getLimit());
        
        $pagination = new LightPagination();
        $pagination->setPageSize(-1);
        
        $this->assertEquals(1, $pagination->getLimit());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
