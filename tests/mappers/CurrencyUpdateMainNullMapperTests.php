<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\CurrencyUpdateMainNullMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\CurrencyUpdateMainNullMapper
 */
class CurrencyUpdateMainNullMapperTests extends \PHPUnit_Framework_TestCase
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
     * Тестирует метод CurrencyUpdateMainNullMapper::setGroup
     */
    public function testSetGroup()
    {
        $currency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}} LIMIT 1')->queryOne();
        $this->assertTrue((bool) $currency['main']);
        
        $currencyUpdateMainNullMapper = new CurrencyUpdateMainNullMapper([
            'tableName'=>'currency',
            'fields'=>['main'],
        ]);
        
        $result = $currencyUpdateMainNullMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $currency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}} LIMIT 1')->queryOne();
        $this->assertFalse((bool) $currency['main']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
