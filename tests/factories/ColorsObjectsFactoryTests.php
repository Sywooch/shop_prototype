<?php

namespace app\tests\factories;

use app\factories\ColorsObjectsFactory;
use app\tests\DbManager;
use app\models\ColorsModel;
use app\mappers\ColorsMapper;
use app\queries\ColorsQueryCreator;

/**
 * Тестирует класс app\factories\ColorsObjectsFactory
 */
class ColorsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод ColorsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'orderByField'=>'color'
        ]);
        
        $this->assertEmpty($colorsMapper->objectsArray);
        $this->assertEmpty($colorsMapper->DbArray);
        
        $colorsMapper->visit(new ColorsQueryCreator());
        
        $colorsMapper->DbArray = \Yii::$app->db->createCommand($colorsMapper->query)->queryAll();
        
        $this->assertFalse(empty($colorsMapper->DbArray));
        
        $colorsMapper->visit(new ColorsObjectsFactory());
        
        $this->assertFalse(empty($colorsMapper->objectsArray));
        $this->assertTrue(is_object($colorsMapper->objectsArray[0]));
        $this->assertTrue($colorsMapper->objectsArray[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($colorsMapper->objectsArray[0], 'color'));
        
        $this->assertTrue(isset($colorsMapper->objectsArray[0]->id));
        $this->assertTrue(isset($colorsMapper->objectsArray[0]->color));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
