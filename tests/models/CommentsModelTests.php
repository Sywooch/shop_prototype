<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\CommentsModel;
use app\models\EmailsModel;

/**
 * Тестирует CommentsModel
 */
class CommentsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_emailId = 13;
    private static $_text = 'Some Text';
    private static $_name = 'Some Name';
    private static $_email = 'some@some.com';
    private static $_email2 = 'another@another.com';
    private static $_notEmail = 'some';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_active = 1;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\CommentsModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_emailId, ':email'=>self::$_email]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new CommentsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'text'));
        $this->assertTrue(property_exists($model, 'name'));
        $this->assertTrue(property_exists($model, 'id_products'));
        $this->assertTrue(property_exists($model, 'active'));
        $this->assertTrue(property_exists($model, 'email'));
        $this->assertTrue(property_exists($model, 'categories'));
        $this->assertTrue(property_exists($model, 'subcategory'));
        $this->assertTrue(property_exists($model, '_id_emails'));
        
        $this->assertTrue(method_exists($model, 'getId_emails'));
        $this->assertTrue(method_exists($model, 'setId_emails'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'text'=>self::$_text, 'name'=>self::$_name, 'email'=>self::$_email, 'id_products'=>self::$_id, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode];
        
        $this->assertTrue(empty($model->id));
        $this->assertFalse(empty($model->text));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->email));
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        
        $this->assertNotEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_text, $model->text);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_email, $model->email);
        $this->assertEquals(self::$_id, $model->id_products);
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'text'=>self::$_text, 'name'=>self::$_name, 'id_emails'=>self::$_id, 'id_products'=>self::$_id, 'active'=>self::$_active];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->text));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->id_emails));
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->active));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_text, $model->text);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_id, $model->id_emails);
        $this->assertEquals(self::$_id, $model->id_products);
        $this->assertEquals(self::$_active, $model->active);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('text', $model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $model->attributes = ['text'=>self::$_text, 'email'=>self::$_email];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        $this->assertFalse(array_key_exists('text', $model->errors));
        $this->assertFalse(array_key_exists('email', $model->errors));
        
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $model->attributes = ['text'=>self::$_text, 'email'=>self::$_notEmail];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
    }
    
    /**
     * Тестирует метод CommentsModel::getId_emails
     */
    public function testGetId_emails()
    {
        $model = new CommentsModel();
        $model->email = self::$_email;
        
        $this->assertFalse(empty($model->id_emails));
        $this->assertEquals(self::$_emailId, $model->id_emails);
        
        $model = new CommentsModel();
        $model->email = self::$_email2;
        
        $this->assertFalse(empty($model->id_emails));
        $this->assertNotEquals(self::$_emailId, $model->id_emails);
        
        $result = \Yii::$app->db->createCommand('SELECT COUNT(*) FROM {{emails}}')->queryScalar();
        
        $this->assertEquals(2, $result);
    }
    
    /**
     * Тестирует метод CommentsModel::setId
     */
    public function testSetId()
    {
        $model = new CommentsModel();
        $model->id_emails = self::$_id + 43;
        
        $this->assertEquals(self::$_id + 43, $model->id_emails);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
