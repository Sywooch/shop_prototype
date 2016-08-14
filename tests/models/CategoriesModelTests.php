<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\{CategoriesModel, 
    SubcategoryModel};
use app\helpers\MappersHelper;

/**
 * Тестирует CategoriesModel
 */
class CategoriesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_name2 = 'Some Name 2';
    private static $_nameFresh = 'Some Fresh Name';
    private static $_categorySeocode = 'mensfootwear';
    private static $_categorySeocode2 = 'mensfootwear2';
    private static $_categorySeocodeFresh = 'fresh';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\CategoriesModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id + 1, ':name'=>self::$_name2, ':seocode'=>self::$_categorySeocode2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new CategoriesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_UPDATE_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DELETE_FORM'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'name'));
        $this->assertTrue(property_exists($model, 'seocode'));
        $this->assertTrue(property_exists($model, '_subcategory'));
        
        $this->assertTrue(method_exists($model, 'getSubcategory'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_categorySeocode];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->seocode));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['name'=>self::$_name, 'seocode'=>self::$_categorySeocode];
        
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->seocode));
        
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_categorySeocode];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->seocode));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_DELETE_FORM]);
        $model->attributes = ['id'=>self::$_id];
        
        $this->assertFalse(empty($model->id));
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_ADD_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['name'=>self::$_name, 'seocode'=>self::$_categorySeocode];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['name'=>self::$_nameFresh, 'seocode'=>self::$_categorySeocodeFresh];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(3, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name2, 'seocode'=>self::$_categorySeocode];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_categorySeocode2];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_nameFresh, 'seocode'=>self::$_categorySeocodeFresh];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_DELETE_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_DELETE_FORM]);
        $model->attributes = ['id'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод CategoriesModel::getSubcategory
     */
    public function testGetSubcategory()
    {
        $model = new CategoriesModel();
        $model->id = self::$_id;
        
        $subcategoryArray = $model->subcategory;
        
        $this->assertTrue(is_array($subcategoryArray));
        $this->assertFalse(empty($subcategoryArray));
        $this->assertTrue(is_object($subcategoryArray[0]));
        $this->assertTrue($subcategoryArray[0] instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует возврат null в методе CategoriesModel::getSubcategory
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetSubcategory()
    {
        $model = new CategoriesModel();
        
        $this->assertTrue(is_null($model->subcategory));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
