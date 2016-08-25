<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\CurrencyInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\CurrencyInsertMapper
 */
class CurrencyInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_currency = 'UAH';
    private static $_exchange_rate = '27.05698';
    private static $_main = true;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод CurrencyInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll()));
        
        $currencyInsertMapper = new CurrencyInsertMapper([
            'tableName'=>'currency',
            'fields'=>['currency', 'exchange_rate', 'main'],
            'objectsArray'=>[
                new MockModel([
                    'currency'=>self::$_currency,
                    'exchange_rate'=>self::$_exchange_rate,
                    'main'=>self::$_main,
                ]),
            ],
        ]);
        
        $result = $currencyInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $result =  \Yii::$app->db->createCommand('SELECT * FROM {{currency}} LIMIT 1')->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_currency, $result['currency']);
        $this->assertEquals(self::$_exchange_rate, $result['exchange_rate']);
        $this->assertEquals(self::$_main, $result['main']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
