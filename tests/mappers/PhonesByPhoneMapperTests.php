<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\PhonesByPhoneMapper;
use app\models\PhonesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\PhonesByPhoneMapper
 */
class PhonesByPhoneMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
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
     * Тестирует метод PhonesByPhoneMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $phonesByPhoneMapper = new PhonesByPhoneMapper([
            'tableName'=>'phones',
            'fields'=>['id', 'phone'],
            'model'=>new PhonesModel([
                'phone'=>self::$_phone,
            ]),
        ]);
        $phonesModel = $phonesByPhoneMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($phonesModel));
        $this->assertTrue($phonesModel instanceof PhonesModel);
        
        //$this->assertTrue(property_exists($phonesModel, 'id'));
        $this->assertTrue(property_exists($phonesModel, 'phone'));
        
        $this->assertTrue(isset($phonesModel->id));
        $this->assertTrue(isset($phonesModel->phone));
        
        $this->assertEquals(self::$_id, $phonesModel->id);
        $this->assertEquals(self::$_phone, $phonesModel->phone);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
