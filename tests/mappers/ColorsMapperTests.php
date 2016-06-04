<?php

namespace app\tests\mappers;

use app\mappers\ColorsMapper;
use app\tests\DbManager;
use app\models\ColorsModel;

/**
 * Тестирует класс app\mappers\ColorsMapper
 */
class ColorsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод ColorsMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = [];
        
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'orderByField'=>'color'
        ]);
        $colorsList = $colorsMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertTrue(isset($colorsList[0]->id));
        $this->assertTrue(isset($colorsList[0]->color));
    }
    
    /**
     * Тестирует метод SizesMapper::getGroup с учетом категории
     */
    public function testGetGroupCategories()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'orderByField'=>'color'
        ]);
        $colorsList = $colorsMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertTrue(isset($colorsList[0]->id));
        $this->assertTrue(isset($colorsList[0]->color));
    }
    
    /**
     * Тестирует метод SizesMapper::getGroup с учетом категории и подкатегории
     */
    public function testGetGroupSubcategories()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'orderByField'=>'color'
        ]);
        $colorsList = $colorsMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertTrue(isset($colorsList[0]->id));
        $this->assertTrue(isset($colorsList[0]->color));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
