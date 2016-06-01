<?php

namespace app\tests\mappers;

use app\mappers\SubcategoryMapper;
use app\tests\DbManager;
use app\models\CategoriesModel;
use app\models\SubcategoryModel;

/**
 * Тестирует класс app\mappers\SubcategoryMapper
 */
class SubcategoryMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод SubcategoryMapper::getGroup
     */
    public function testGetGroup()
    {
        $categoryModel = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_DB]);
        $categoryModel->attributes = ['id'=>1];
        
        $subcategoryMapper = new SubcategoryMapper(['tableName'=>'subcategory', 'fields'=>['id', 'name'], 'model'=>$categoryModel]);
        $subcategoryList = $subcategoryMapper->getGroup();
        
        $this->assertTrue(is_array($subcategoryList));
        $this->assertFalse(empty($subcategoryList));
        $this->assertTrue(is_object($subcategoryList[0]));
        $this->assertTrue($subcategoryList[0] instanceof SubcategoryModel);
        
        $this->assertTrue(property_exists($subcategoryList[0], 'id'));
        $this->assertTrue(property_exists($subcategoryList[0], 'name'));
        
        $this->assertTrue(isset($subcategoryList[0]->id));
        $this->assertTrue(isset($subcategoryList[0]->name));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
