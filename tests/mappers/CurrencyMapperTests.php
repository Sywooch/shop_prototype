<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\CurrencyMapper;
use app\models\CurrencyModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\CurrencyMapper
 */
class CurrencyMapperTests extends \PHPUnit_Framework_TestCase
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
     * Тестирует метод CurrencyMapper::getGroup
     */
    public function testGetGroup()
    {
        $currencyMapper = new CurrencyMapper([
            'tableName'=>'currency',
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
        ]);
        $currencyList = $currencyMapper->getGroup();
        
        $this->assertTrue(is_array($currencyList));
        $this->assertFalse(empty($currencyList));
        $this->assertTrue(is_object($currencyList[0]));
        $this->assertTrue($currencyList[0] instanceof CurrencyModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
