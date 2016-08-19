<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\ColorsByColorMapper;
use app\models\ColorsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ColorsByColorMapper
 */
class ColorsByColorMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_color = 'brown';
    
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
     * Тестирует метод ColorsByColorMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $colorsByColorMapper = new ColorsByColorMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'model'=>new ColorsModel([
                'color'=>self::$_color,
            ]),
        ]);
        $colorsModel = $colorsByColorMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($colorsModel));
        $this->assertTrue($colorsModel instanceof ColorsModel);
        
        $this->assertEquals(self::$_id, $colorsModel->id);
        $this->assertEquals(self::$_color, $colorsModel->color);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
