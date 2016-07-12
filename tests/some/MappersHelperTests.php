<?php

namespace app\tests\helpers;

use app\tests\DbManager;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;
use app\models\CurrencyModel;
use app\models\ColorsModel;
use app\models\SizesModel;
use app\models\BrandsModel;

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
    private static $_subcategorySeocode = 'boots';
    private static $_currency = 'EUR';
    private static $_color = 'gray';
    private static $_size = '46';
    private static $_brand = 'Some Brand';
    
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
        
        # categories была добавлена в MappersHelperTests::testGetCategoriesList()
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id, ':id_colors'=>self::$_id]);
        $command->execute();
        
        $result = MappersHelper::getColorsList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof ColorsModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_color, $result[0]->color);
    }
    
    /**
     * Тестирует метод MappersHelper::getSizesList
     */
    public function testGetSizesList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        # categories была добавлена в MappersHelperTests::testGetCategoriesList()
        # subcategory была добавлена в MappersHelperTests::testGetColorsList()
        # products была добавлена в MappersHelperTests::testGetColorsList()
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        $result = MappersHelper::getSizesList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof SizesModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_size, $result[0]->size);
    }
    
    /**
     * Тестирует метод MappersHelper::getBrandsList
     */
    public function testGetBrandsList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
        $command->execute();
        
        # categories была добавлена в MappersHelperTests::testGetCategoriesList()
        # subcategory была добавлена в MappersHelperTests::testGetColorsList()
        # products была добавлена в MappersHelperTests::testGetColorsList()
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_brands}} SET [[id_products]]=:id_products, [[id_brands]]=:id_brands');
        $command->bindValues([':id_products'=>self::$_id, ':id_brands'=>self::$_id]);
        $command->execute();
        
        $result = MappersHelper::getBrandsList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof BrandsModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_brand, $result[0]->brand);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
