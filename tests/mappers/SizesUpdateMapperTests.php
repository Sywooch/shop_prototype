<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\SizesUpdateMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SizesUpdateMapper
 */
class SizesUpdateMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 2;
    private static $_size = '45';
    private static $_size2 = '36';
    
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
     * Тестирует метод SizesUpdateMapper::setGroup
     */
    public function testSetGroup()
    {
        $sizesUpdateMapper = new SizesUpdateMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_id, 
                    'size'=>self::$_size2, 
                ]),
            ],
        ]);
        $result = $sizesUpdateMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{sizes}} WHERE [[sizes.id]]=:id');
        $command->bindValue(':id', self::$_id);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_id, $result['id']);
        $this->assertEquals(self::$_size2, $result['size']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
