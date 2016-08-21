<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\SizesInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SizesInsertMapper
 */
class SizesInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_size = '46';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SizesInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{sizes}}')->queryAll()));
        
        $sizesInsertMapper = new SizesInsertMapper([
            'tableName'=>'sizes',
            'fields'=>['size'],
            'objectsArray'=>[
                new MockModel([
                    'size'=>self::$_size, 
                ])
            ],
        ]);
        
        $result = $sizesInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $result =  \Yii::$app->db->createCommand('SELECT * FROM {{sizes}} LIMIT 1')->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_size, $result['size']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
