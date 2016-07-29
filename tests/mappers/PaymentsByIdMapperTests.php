<?php

namespace app\tests\mappers;

use app\tests\{DbManager, MockModel};
use app\mappers\PaymentsByIdMapper;
use app\models\PaymentsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\PaymentsByIdMapper
 */
class PaymentsByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{payments}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод PaymentsByIdMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $paymentsByIdMapper = new PaymentsByIdMapper([
            'tableName'=>'payments',
            'fields'=>['id', 'name', 'description'],
            'model'=>new PaymentsModel([
                'id'=>self::$_id,
            ]),
        ]);
        $paymentsModel = $paymentsByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($paymentsModel));
        $this->assertTrue($paymentsModel instanceof PaymentsModel);
        
        //$this->assertTrue(property_exists($paymentsModel, 'id'));
        $this->assertTrue(property_exists($paymentsModel, 'name'));
        $this->assertTrue(property_exists($paymentsModel, 'description'));
        
        $this->assertTrue(isset($paymentsModel->id));
        $this->assertTrue(isset($paymentsModel->name));
        $this->assertTrue(isset($paymentsModel->description));
        
        $this->assertEquals(self::$_name, $paymentsModel->name);
        $this->assertEquals(self::$_description, $paymentsModel->description);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
