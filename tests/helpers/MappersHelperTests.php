<?php

namespace app\tests\helpers;

use app\tests\DbManager;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;
use app\models\CurrencyModel;
use app\models\ColorsModel;

/**
 * Тестирует класс app\helpers\MappersHelper
 */
class MappersHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_categorySeocode = 'mensfootwear';
    private static $_currency = 'EUR';
    private static $_color = 'gray';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_reflectionClass = new \ReflectionClass('app\helpers\MappersHelper');
    }
    
    /**
     * Тестирует наличие свойств и методов в классе app\helpers\MappersHelper
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasProperty('_categoriesList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_currencyList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_colorsList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_sizesList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_brandsList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_addressModel'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_phonesModel'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_deliveriesModel'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_paymentsModel'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_emailsModel'));
        
        $this->assertTrue(self::$_reflectionClass->hasMethod('getCategoriesList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getСurrencyList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getColorsList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getSizesList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getBrandsList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getAddressModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getPhonesModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getDeliveriesModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getPaymentsModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('setUsersPurchases'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('setOrUpdateUsers'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getEmailsModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('setUsersRules'));
    }
    
    /**
     * Тестирует метод MappersHelper::getCategoriesList
     */
    public function testGetCategoriesList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $result = MappersHelper::getCategoriesList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof CategoriesModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_name, $result[0]->name);
        $this->assertEquals(self::$_categorySeocode, $result[0]->seocode);
    }
    
    /**
     * Тестирует метод MappersHelper::getСurrencyList
     */
    public function testGetСurrencyList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency');
        $command->bindValues([':id'=>self::$_id, ':currency'=>self::$_currency]);
        $command->execute();
        
        $result = MappersHelper::getСurrencyList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof CurrencyModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_currency, $result[0]->currency);
    }
    
    /**
     * Тестирует метод MappersHelper::getColorsList
     */
    public function testGetColorsList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $result = MappersHelper::getColorsList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof ColorsModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_color, $result[0]->color);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
