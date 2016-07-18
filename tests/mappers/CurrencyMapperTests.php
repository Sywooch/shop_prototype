<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\CurrencyMapper;
use app\models\CurrencyModel;

/**
 * Тестирует класс app\mappers\CurrencyMapper
 */
class CurrencyMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency');
        $command->bindValues([':id'=>self::$_id, ':currency'=>self::$_currency]);
        $command->execute();
    }
    
    /**
     * Тестирует метод CurrencyMapper::getGroup
     */
    public function testGetGroup()
    {
        $currencyMapper = new CurrencyMapper([
            'tableName'=>'currency',
            'fields'=>['id', 'currency'],
        ]);
        $currencyList = $currencyMapper->getGroup();
        
        $this->assertTrue(is_array($currencyList));
        $this->assertFalse(empty($currencyList));
        $this->assertTrue(is_object($currencyList[0]));
        $this->assertTrue($currencyList[0] instanceof CurrencyModel);
        
        $this->assertTrue(property_exists($currencyList[0], 'id'));
        $this->assertTrue(property_exists($currencyList[0], 'currency'));
        
        $this->assertTrue(isset($currencyList[0]->id));
        $this->assertTrue(isset($currencyList[0]->currency));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
