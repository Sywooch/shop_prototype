<?php

namespace app\tests\mappers;

use app\mappers\CategoriesMapper;
use app\tests\DbManager;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\mappers\CategoriesMapper
 */
class CategoriesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод CategoriesMapper::getGroup
     */
    public function testGetGroup()
    {
        $categoriesMapper = new CategoriesMapper([
            'tableName'=>'categories',
            'fields'=>['id', 'name'],
        ]);
        $categoriesList = $categoriesMapper->getGroup();
        
        $this->assertTrue(is_array($categoriesList));
        $this->assertFalse(empty($categoriesList));
        $this->assertTrue(is_object($categoriesList[0]));
        $this->assertTrue($categoriesList[0] instanceof CategoriesModel);
        
        $this->assertTrue(property_exists($categoriesList[0], 'id'));
        $this->assertTrue(property_exists($categoriesList[0], 'name'));
        
        $this->assertTrue(isset($categoriesList[0]->id));
        $this->assertTrue(isset($categoriesList[0]->name));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
