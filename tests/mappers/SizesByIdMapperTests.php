<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\SizesByIdMapper;
use app\models\SizesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SizesByIdMapper
 */
class SizesByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_size = '46';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SizesByIdMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $sizesByIdMapper = new SizesByIdMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'model'=>new SizesModel([
                'id'=>self::$_id,
            ]),
        ]);
        $result = $sizesByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof SizesModel);
        
        $this->assertFalse(empty($result->id));
        $this->assertFalse(empty($result->size));
        
        $this->assertEquals(self::$_id, $result->id);
        $this->assertEquals(self::$_size, $result->size);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
