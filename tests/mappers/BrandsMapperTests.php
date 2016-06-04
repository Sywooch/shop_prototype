<?php

namespace app\tests\mappers;

use app\mappers\BrandsMapper;
use app\tests\DbManager;
use app\models\BrandsModel;

/**
 * Тестирует класс app\mappers\BrandsMapper
 */
class BrandsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод BrandsMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = [];
        
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'orderByField'=>'brand'
        ]);
        $brandsList = $brandsMapper->getGroup();
        
        $this->assertTrue(is_array($brandsList));
        $this->assertFalse(empty($brandsList));
        $this->assertTrue(is_object($brandsList[0]));
        $this->assertTrue($brandsList[0] instanceof BrandsModel);
        
        $this->assertTrue(property_exists($brandsList[0], 'id'));
        $this->assertTrue(property_exists($brandsList[0], 'brand'));
        
        $this->assertTrue(isset($brandsList[0]->id));
        $this->assertTrue(isset($brandsList[0]->brand));
    }
    
    /**
     * Тестирует метод BrandsMapper::getGroup с учетом категории
     */
    public function testGetGroupCategories()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'orderByField'=>'brand'
        ]);
        $brandsList = $brandsMapper->getGroup();
        
        $this->assertTrue(is_array($brandsList));
        $this->assertFalse(empty($brandsList));
        $this->assertTrue(is_object($brandsList[0]));
        $this->assertTrue($brandsList[0] instanceof BrandsModel);
        
        $this->assertTrue(property_exists($brandsList[0], 'id'));
        $this->assertTrue(property_exists($brandsList[0], 'brand'));
        
        $this->assertTrue(isset($brandsList[0]->id));
        $this->assertTrue(isset($brandsList[0]->brand));
    }
    
    /**
     * Тестирует метод BrandsMapper::getGroup с учетом категории и подкатегории
     */
    public function testGetGroupSubcategories()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'orderByField'=>'brand'
        ]);
        $brandsList = $brandsMapper->getGroup();
        
        $this->assertTrue(is_array($brandsList));
        $this->assertFalse(empty($brandsList));
        $this->assertTrue(is_object($brandsList[0]));
        $this->assertTrue($brandsList[0] instanceof BrandsModel);
        
        $this->assertTrue(property_exists($brandsList[0], 'id'));
        $this->assertTrue(property_exists($brandsList[0], 'brand'));
        
        $this->assertTrue(isset($brandsList[0]->id));
        $this->assertTrue(isset($brandsList[0]->brand));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
