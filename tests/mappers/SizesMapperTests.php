<?php

namespace app\tests\mappers;

use app\mappers\SizesMapper;
use app\tests\DbManager;
use app\models\SizesModel;

/**
 * Тестирует класс app\mappers\SizesMapper
 */
class SizesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод SizesMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = [];
        
        $sizesMapper = new SizesMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size'
        ]);
        $sizesList = $sizesMapper->getGroup();
        
        $this->assertTrue(is_array($sizesList));
        $this->assertFalse(empty($sizesList));
        $this->assertTrue(is_object($sizesList[0]));
        $this->assertTrue($sizesList[0] instanceof SizesModel);
        
        $this->assertTrue(property_exists($sizesList[0], 'id'));
        $this->assertTrue(property_exists($sizesList[0], 'size'));
        
        $this->assertTrue(isset($sizesList[0]->id));
        $this->assertTrue(isset($sizesList[0]->size));
    }
    
    /**
     * Тестирует метод SizesMapper::getGroup с учетом категории
     */
    public function testGetGroupCategories()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $sizesMapper = new SizesMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size'
        ]);
        $sizesList = $sizesMapper->getGroup();
        
        $this->assertTrue(is_array($sizesList));
        $this->assertFalse(empty($sizesList));
        $this->assertTrue(is_object($sizesList[0]));
        $this->assertTrue($sizesList[0] instanceof SizesModel);
        
        $this->assertTrue(property_exists($sizesList[0], 'id'));
        $this->assertTrue(property_exists($sizesList[0], 'size'));
        
        $this->assertTrue(isset($sizesList[0]->id));
        $this->assertTrue(isset($sizesList[0]->size));
    }
    
    /**
     * Тестирует метод SizesMapper::getGroup с учетом категории и подкатегории
     */
    public function testGetGroupSubcategories()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $sizesMapper = new SizesMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size'
        ]);
        $sizesList = $sizesMapper->getGroup();
        
        $this->assertTrue(is_array($sizesList));
        $this->assertFalse(empty($sizesList));
        $this->assertTrue(is_object($sizesList[0]));
        $this->assertTrue($sizesList[0] instanceof SizesModel);
        
        $this->assertTrue(property_exists($sizesList[0], 'id'));
        $this->assertTrue(property_exists($sizesList[0], 'size'));
        
        $this->assertTrue(isset($sizesList[0]->id));
        $this->assertTrue(isset($sizesList[0]->size));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
