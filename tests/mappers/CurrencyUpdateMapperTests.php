<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\CurrencyUpdateMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\CurrencyUpdateMapper
 */
class CurrencyUpdateMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    private static $_currency2 = 'UAH';
    private static $_exchange_rate = 12.456;
    private static $_exchange_rate2 = 0.13956;
    private static $_main = true;
    private static $_mainFalse = false;
    
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
     * Тестирует метод CurrencyUpdateMapper::setGroup
     */
    public function testSetGroup()
    {
        $currencyUpdateMapper = new CurrencyUpdateMapper([
            'tableName'=>'currency',
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_id, 
                    'currency'=>self::$_currency2,
                    'exchange_rate'=>self::$_exchange_rate2,
                    'main'=>self::$_mainFalse, 
                ]),
            ],
        ]);
        
        $result = $currencyUpdateMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{currency}} WHERE [[currency.id]]=:id');
        $command->bindValue(':id', self::$_id);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_id, $result['id']);
        $this->assertEquals(self::$_currency2, $result['currency']);
        $this->assertEquals(self::$_exchange_rate2, $result['exchange_rate']);
        $this->assertEquals(self::$_mainFalse, (bool) $result['main']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
