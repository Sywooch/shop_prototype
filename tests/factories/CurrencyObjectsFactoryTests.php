<?php

namespace app\tests\factories;

use app\factories\CurrencyObjectsFactory;
use app\tests\DbManager;
use app\models\CurrencyModel;
use app\mappers\CurrencyMapper;
use app\queries\CurrencyQueryCreator;

/**
 * Тестирует класс app\factories\CurrencyObjectsFactory
 */
class CurrencyObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод CurrencyObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $currencyMapper = new CurrencyMapper([
            'tableName'=>'currency',
            'fields'=>['id', 'currency'],
            'orderByField'=>'currency'
        ]);
        
        $this->assertEmpty($currencyMapper->objectsArray);
        $this->assertEmpty($currencyMapper->DbArray);
        
        $currencyMapper->visit(new CurrencyQueryCreator());
        
        $currencyMapper->DbArray = \Yii::$app->db->createCommand($currencyMapper->query)->queryAll();
        
        $this->assertFalse(empty($currencyMapper->DbArray));
        
        $currencyMapper->visit(new CurrencyObjectsFactory());
        
        $this->assertFalse(empty($currencyMapper->objectsArray));
        $this->assertTrue(is_object($currencyMapper->objectsArray[0]));
        $this->assertTrue($currencyMapper->objectsArray[0] instanceof CurrencyModel);
        
        $this->assertTrue(property_exists($currencyMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($currencyMapper->objectsArray[0], 'currency'));
        
        $this->assertTrue(isset($currencyMapper->objectsArray[0]->id));
        $this->assertTrue(isset($currencyMapper->objectsArray[0]->currency));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
