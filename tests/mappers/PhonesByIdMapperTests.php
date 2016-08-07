<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\PhonesByIdMapper;
use app\models\PhonesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\PhonesByIdMapper
 */
class PhonesByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 12;
    private static $_phone = '+396548971203';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{phones}} SET [[id]]=:id, [[phone]]=:phone');
        $command->bindValues([':id'=>self::$_id, ':phone'=>self::$_phone]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод PhonesByIdMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $PhonesByIdMapper = new PhonesByIdMapper([
            'tableName'=>'phones',
            'fields'=>['id', 'phone'],
            'model'=>new PhonesModel([
                'id'=>self::$_id,
            ]),
        ]);
        $phonesModel = $PhonesByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($phonesModel));
        $this->assertTrue($phonesModel instanceof PhonesModel);
        
        //$this->assertTrue(property_exists($phonesModel, 'id'));
        $this->assertTrue(property_exists($phonesModel, 'phone'));
        
        $this->assertFalse(empty($phonesModel->id));
        $this->assertFalse(empty($phonesModel->phone));
        
        $this->assertEquals(self::$_id, $phonesModel->id);
        $this->assertEquals(self::$_phone, $phonesModel->phone);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
