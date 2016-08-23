<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\{SubcategoryModel, 
    CategoriesModel};

/**
 * Тестирует app\models\SubcategoryModel
 */
class SubcategoryModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_name2 = 'Some name 2';
    private static $_nameFresh = 'Some name fresh';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_subcategorySeocode2 = 'boots 2';
    private static $_subcategorySeocodeFresh = 'sneakers';
    private static $_nameMessage = 'Подкатегория уже существует!';
    private static $_seocodeMessage = 'Этот код уже используется!';
    private static $_foreignMessage = 'С подкатегорией связаны товары! Необходимо перенести их перед удалением!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\SubcategoryModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id + 1, ':name'=>self::$_name2, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new SubcategoryModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_UPDATE_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DELETE_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'name'));
        $this->assertTrue(property_exists($model, 'seocode'));
        $this->assertTrue(property_exists($model, 'id_categories'));
        $this->assertTrue(property_exists($model, '_categories'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_categorySeocode, 'id_categories'=>self::$_id];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        $this->assertEquals(self::$_id, $model->id_categories);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['name'=>self::$_name, 'seocode'=>self::$_categorySeocode, 'id_categories'=>self::$_id];
        
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        $this->assertEquals(self::$_id, $model->id_categories);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_categorySeocode, 'id_categories'=>self::$_id];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        $this->assertEquals(self::$_id, $model->id_categories);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_DELETE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_categorySeocode, 'id_categories'=>self::$_id];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        $this->assertEquals(self::$_id, $model->id_categories);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_FORM]);
        $model->attributes = ['name'=>self::$_name, 'seocode'=>self::$_categorySeocode, 'id_categories'=>self::$_id];
        
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        $this->assertEquals(self::$_id, $model->id_categories);
    }
    
    /**
     * Тестирует превила проверки
     */
    public function testRules()
    {
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_ADD_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(3, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        $this->assertTrue(array_key_exists('id_categories', $model->errors));
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['name'=>self::$_name, 'seocode'=>self::$_subcategorySeocodeFresh, 'id_categories'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertEquals(self::$_nameMessage, $model->errors['name'][0]);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['name'=>self::$_nameFresh, 'seocode'=>self::$_subcategorySeocode, 'id_categories'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        $this->assertEquals(self::$_seocodeMessage, $model->errors['seocode'][0]);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['name'=>self::$_nameFresh, 'seocode'=>self::$_subcategorySeocodeFresh, 'id_categories'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(4, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        $this->assertTrue(array_key_exists('id_categories', $model->errors));
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name2, 'seocode'=>self::$_subcategorySeocodeFresh, 'id_categories'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertEquals(self::$_nameMessage, $model->errors['name'][0]);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_nameFresh, 'seocode'=>self::$_subcategorySeocode2, 'id_categories'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        $this->assertEquals(self::$_seocodeMessage, $model->errors['seocode'][0]);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_nameFresh, 'seocode'=>self::$_subcategorySeocodeFresh, 'id_categories'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_DELETE_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(4, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        $this->assertTrue(array_key_exists('id_categories', $model->errors));
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_DELETE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_subcategorySeocode, 'id_categories'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertEquals(self::$_foreignMessage, $model->errors['name'][0]);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_DELETE_FORM]);
        $model->attributes = ['id'=>self::$_id + 1, 'name'=>self::$_name2, 'id_categories'=>self::$_id, 'seocode'=>self::$_subcategorySeocode2];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод SubcategoryModel::getCategories
     */
    public function testGetCategories()
    {
        $model = new SubcategoryModel();
        $model->id_categories = self::$_id;
        
        $result = $model->categories;
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof CategoriesModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
