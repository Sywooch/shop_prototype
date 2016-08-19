<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\ColorsUpdateMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ColorsUpdateMapper
 */
class ColorsUpdateMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 2;
    private static $_color = 'gray';
    private static $_color2 = 'purple';
    
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
     * Тестирует метод ColorsUpdateMapper::setGroup
     */
    public function testSetGroup()
    {
        $colorsUpdateMapper = new ColorsUpdateMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_id, 
                    'color'=>self::$_color2, 
                ]),
            ],
        ]);
        $result = $colorsUpdateMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{colors}} WHERE [[colors.id]]=:id');
        $command->bindValue(':id', self::$_id);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_id, $result['id']);
        $this->assertEquals(self::$_color2, $result['color']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
