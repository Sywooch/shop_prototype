<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\BrandsInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\BrandsInsertMapper
 */
class BrandsInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_brand = 'Dining massacre';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод BrandsInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{brands}}')->queryAll()));
        
        $brandsInsertMapper = new BrandsInsertMapper([
            'tableName'=>'brands',
            'fields'=>['brand'],
            'objectsArray'=>[
                new MockModel([
                    'brand'=>self::$_brand, 
                ])
            ],
        ]);
        
        $result = $brandsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{brands}} WHERE [[brands.brand]]=:brand');
        $command->bindValue(':brand', self::$_brand);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_brand, $result['brand']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
