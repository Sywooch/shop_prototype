<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\ColorsInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ColorsInsertMapper
 */
class ColorsInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_color = 'red';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ColorsInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{colors}}')->queryAll()));
        
        $colorsInsertMapper = new ColorsInsertMapper([
            'tableName'=>'colors',
            'fields'=>['color'],
            'objectsArray'=>[
                new MockModel([
                    'color'=>self::$_color, 
                ])
            ],
        ]);
        
        $result = $colorsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $result =  \Yii::$app->db->createCommand('SELECT * FROM {{colors}} LIMIT 1')->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_color, $result['color']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
