<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\CurrencyByIdMapper;
use app\models\CurrencyModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\CurrencyByIdMapper
 */
class CurrencyByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    private static $_exchange_rate = '12.456';
    private static $_main = '1';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency, [[exchange_rate]]=:exchange_rate, [[main]]=:main');
        $command->bindValues([':id'=>self::$_id, ':currency'=>self::$_currency, ':exchange_rate'=>self::$_exchange_rate, ':main'=>self::$_main]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод CurrencyByIdMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $currencyByIdMapper = new CurrencyByIdMapper([
            'tableName'=>'currency',
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
            'model'=>new CurrencyModel([
                'id'=>self::$_id,
            ]),
        ]);
        $currencyModel = $currencyByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($currencyModel));
        $this->assertTrue($currencyModel instanceof CurrencyModel);
        
        $this->assertTrue(property_exists($currencyModel, 'id'));
        $this->assertTrue(property_exists($currencyModel, 'currency'));
        $this->assertTrue(property_exists($currencyModel, 'exchange_rate'));
        $this->assertTrue(property_exists($currencyModel, 'main'));
        
        $this->assertFalse(empty($currencyModel->id));
        $this->assertFalse(empty($currencyModel->currency));
        $this->assertFalse(empty($currencyModel->exchange_rate));
        $this->assertFalse(empty($currencyModel->main));
        
        $this->assertEquals(self::$_id, $currencyModel->id);
        $this->assertEquals(self::$_currency, $currencyModel->currency);
        $this->assertEquals(self::$_exchange_rate, $currencyModel->exchange_rate);
        $this->assertEquals(self::$_main, $currencyModel->main);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
