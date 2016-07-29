<?php

namespace app\tests\mappers;

use app\tests\{DbManager, MockModel};
use app\mappers\PhonesInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\PhonesInsertMapper
 */
class PhonesInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_phone = '+380654568978';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод PhonesInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $phonesInsertMapper = new PhonesInsertMapper([
            'tableName'=>'phones',
            'fields'=>['phone'],
            'objectsArray'=>[
                new MockModel([
                    'phone'=>self::$_phone,
                ]),
            ],
        ]);
        $result = $phonesInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{phones}} WHERE [[phones.phone]]=:phone');
        $command->bindValue(':phone', self::$_phone);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('phone', $result);
        
        $this->assertEquals(self::$_phone, $result['phone']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
