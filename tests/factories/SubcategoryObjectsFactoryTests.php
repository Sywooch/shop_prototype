<?php

namespace app\tests\factories;

use app\factories\SubcategoryObjectsFactory;
use app\tests\DbManager;
use app\mappers\SubcategoryMapper;
use app\queries\SubcategoryQueryCreator;
use app\models\SubcategoryModel;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\factories\SubcategoryObjectsFactory
 */
class SubcategoryObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод SubcategoryObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $subcategoryMapper = new SubcategoryMapper([
            'tableName'=>'subcategory',
            'fields'=>['id', 'name'],
        ]);
        
        $this->assertEmpty($subcategoryMapper->objectsArray);
        $this->assertEmpty($subcategoryMapper->DbArray);
        
        $subcategoryMapper->visit(new SubcategoryQueryCreator());
        
        $command = \Yii::$app->db->createCommand($subcategoryMapper->query);
        $command->bindValue(':' . \Yii::$app->params['idKey'], 1);
        $subcategoryMapper->DbArray = $command->queryAll();
        
        $this->assertFalse(empty($subcategoryMapper->DbArray));
        
        $subcategoryMapper->visit(new SubcategoryObjectsFactory());
        
        $this->assertFalse(empty($subcategoryMapper->objectsArray));
        $this->assertTrue(is_object($subcategoryMapper->objectsArray[0]));
        $this->assertTrue($subcategoryMapper->objectsArray[0] instanceof SubcategoryModel);
        
        $this->assertTrue(property_exists($subcategoryMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($subcategoryMapper->objectsArray[0], 'name'));
        
        $this->assertTrue(isset($subcategoryMapper->objectsArray[0]->id));
        $this->assertTrue(isset($subcategoryMapper->objectsArray[0]->name));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
