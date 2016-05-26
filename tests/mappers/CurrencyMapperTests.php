<?php

namespace app\tests\mappers;

use app\mappers\CurrencyMapper;
use app\tests\DbManager;
use app\models\CurrencyModel;

/**
 * Тестирует класс app\mappers\CurrencyMapper
 */
class CurrencyMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод CurrencyMapper::getGroup
     */
    public function testGetGroup()
    {
        $currencyMapper = new CurrencyMapper([
            'tableName'=>'currency',
            'fields'=>['id', 'currency'],
            'orderByField'=>'currency'
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
        self::$dbClass->deleteDb();
    }
}
