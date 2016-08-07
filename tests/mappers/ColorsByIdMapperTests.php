<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\ColorsByIdMapper;
use app\models\ColorsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ColorsByIdMapper
 */
class ColorsByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_color = 'gray';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ColorsByIdMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $colorsByIdMapper = new ColorsByIdMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'model'=>new ColorsModel([
                'id'=>self::$_id,
            ]),
        ]);
        $colorsModel = $colorsByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($colorsModel));
        $this->assertTrue($colorsModel instanceof ColorsModel);
        
        $this->assertFalse(empty($colorsModel->id));
        $this->assertFalse(empty($colorsModel->color));
        
        $this->assertEquals(self::$_id, $colorsModel->id);
        $this->assertEquals(self::$_color, $colorsModel->color);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
