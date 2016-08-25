<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\CurrencyByCurrencyMapper;
use app\models\CurrencyModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\CurrencyByCurrencyMapper
 */
class CurrencyByCurrencyMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    private static $_exchange_rate = '12.456';
    private static $_main = true;
    
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
     * Тестирует метод CurrencyByCurrencyMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $currencyByCurrencyMapper = new CurrencyByCurrencyMapper([
            'tableName'=>'currency',
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
            'model'=>new CurrencyModel([
                'currency'=>self::$_currency,
            ]),
        ]);
        
        $currencyModel = $currencyByCurrencyMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($currencyModel));
        $this->assertTrue($currencyModel instanceof CurrencyModel);
        
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
