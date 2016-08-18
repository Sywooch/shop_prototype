<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\BrandsUpdateMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\BrandsUpdateMapper
 */
class BrandsUpdateMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 2;
    private static $_brand = 'Nightmare';
    private static $_brand2 = 'Daymare';
    
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
     * Тестирует метод BrandsUpdateMapper::setGroup
     */
    public function testSetGroup()
    {
        $brandsUpdateMapper = new BrandsUpdateMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_id, 
                    'brand'=>self::$_brand2, 
                ]),
            ],
        ]);
        $result = $brandsUpdateMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{brands}} WHERE [[brands.id]]=:id');
        $command->bindValue(':id', self::$_id);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_id, $result['id']);
        $this->assertEquals(self::$_brand2, $result['brand']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
