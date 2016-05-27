<?php

namespace app\tests\factories;

use app\factories\SizesObjectsFactory;
use app\tests\DbManager;
use app\models\SizesModel;
use app\mappers\SizesMapper;
use app\queries\SizesQueryCreator;

/**
 * Тестирует класс app\factories\SizesObjectsFactory
 */
class SizesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод SizesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $sizesMapper = new SizesMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size'
        ]);
        
        $this->assertEmpty($sizesMapper->objectsArray);
        $this->assertEmpty($sizesMapper->DbArray);
        
        $sizesMapper->visit(new SizesQueryCreator());
        
        $sizesMapper->DbArray = \Yii::$app->db->createCommand($sizesMapper->query)->queryAll();
        
        $this->assertFalse(empty($sizesMapper->DbArray));
        
        $sizesMapper->visit(new SizesObjectsFactory());
        
        $this->assertFalse(empty($sizesMapper->objectsArray));
        $this->assertTrue(is_object($sizesMapper->objectsArray[0]));
        $this->assertTrue($sizesMapper->objectsArray[0] instanceof SizesModel);
        
        $this->assertTrue(property_exists($sizesMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($sizesMapper->objectsArray[0], 'size'));
        
        $this->assertTrue(isset($sizesMapper->objectsArray[0]->id));
        $this->assertTrue(isset($sizesMapper->objectsArray[0]->size));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
