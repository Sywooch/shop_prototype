<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\BrandsDeleteMapper;
use app\helpers\MappersHelper;
use app\models\BrandsModel;

/**
 * Тестирует класс app\mappers\BrandsDeleteMapper
 */
class BrandsDeleteMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 3;
    private static $_brand = 'Baracuda';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод BrandsDeleteMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{brands}}')->queryAll()));
        
        $brandsDeleteMapper = new BrandsDeleteMapper([
            'tableName'=>'brands',
            'objectsArray'=>[
                new BrandsModel(['id'=>self::$_id]),
            ],
        ]);
        
        $result = $brandsDeleteMapper->setGroup();
        
        $this->assertEquals(1, $result);
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{brands}}')->queryAll()));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
