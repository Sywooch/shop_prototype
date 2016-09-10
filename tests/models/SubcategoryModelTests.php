<?php

namespace app\tests\models;

use app\tests\DbManager;
use app\models\{CategoriesModel,
    SubcategoryModel};

/**
 * Тестирует класс app\models\SubcategoryModel
 */
class SubcategoryModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\SubcategoryModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_category]]=:id_category, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_category'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\SubcategoryModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $model = new SubcategoryModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('seocode', $model->attributes));
        $this->assertTrue(array_key_exists('id_category', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_categorySeocode, 'id_category'=>self::$_id];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        $this->assertEquals(self::$_id, $model->id_category);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_categorySeocode, 'id_category'=>self::$_id];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_categorySeocode, $model->seocode);
        $this->assertEquals(self::$_id, $model->id_category);
    }
    
    /**
     * Тестирует метод SubcategoryModel::getCategories
     */
    public function testGetCategories()
    {
        $model = SubcategoryModel::find()->where(['subcategory.id'=>self::$_id])->one();
        
        $this->assertTrue(is_object($model->categories));
        $this->assertTrue($model->categories instanceof CategoriesModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
