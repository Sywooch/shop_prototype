<?php

namespace app\tests\factories;

use app\factories\CategoriesObjectsFactory;
use app\tests\DbManager;
use app\models\CategoriesModel;
use app\mappers\CategoriesMapper;
use app\queries\CategoriesQueryCreator;

/**
 * Тестирует класс app\factories\CategoriesObjectsFactory
 */
class CategoriesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод CategoriesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $categoriesMapper = new CategoriesMapper([
            'tableName'=>'categories',
            'fields'=>['id', 'name'],
        ]);
        
        $this->assertEmpty($categoriesMapper->objectsArray);
        $this->assertEmpty($categoriesMapper->DbArray);
        
        $_GET = array();
        
        $categoriesMapper->visit(new CategoriesQueryCreator());
        
        $categoriesMapper->DbArray = \Yii::$app->db->createCommand($categoriesMapper->query)->queryAll();
        
        $this->assertFalse(empty($categoriesMapper->DbArray));
        
        $categoriesMapper->visit(new CategoriesObjectsFactory());
        
        $this->assertFalse(empty($categoriesMapper->objectsArray));
        $this->assertTrue(is_object($categoriesMapper->objectsArray[0]));
        $this->assertTrue($categoriesMapper->objectsArray[0] instanceof CategoriesModel);
        
        $this->assertTrue(property_exists($categoriesMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($categoriesMapper->objectsArray[0], 'name'));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
