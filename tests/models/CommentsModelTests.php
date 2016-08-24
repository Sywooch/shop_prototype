<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\{CommentsModel,
    EmailsModel,
    ProductsModel};
use app\helpers\MappersHelper;

/**
 * Тестирует app\models\CommentsModel
 */
class CommentsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_text = 'Some Text';
    private static $_name = 'Some Name';
    private static $_active = true;
    private static $_email = 'some@some.com';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\CommentsModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
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
        $model = new CommentsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_UPDATE_CUT'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('id'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('text'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('name'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('id_emails'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('id_products'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('active'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_emails'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_products'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $model->attributes = ['text'=>self::$_text, 'name'=>self::$_name, 'active'=>self::$_active];
        
        $this->assertEquals(self::$_text, $model->text);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_active, $model->active);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'text'=>self::$_text, 'name'=>self::$_name, 'id_emails'=>self::$_id, 'id_products'=>self::$_id, 'active'=>self::$_active];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_text, $model->text);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_id, $model->id_emails);
        $this->assertEquals(self::$_id, $model->id_products);
        $this->assertEquals(self::$_active, $model->active);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FOR_UPDATE_CUT]);
        $model->attributes = ['id'=>self::$_id, 'active'=>self::$_active];
        
        $this->assertEquals(self::$_id, $model->id);
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
        $this->assertTrue(array_key_exists('name', $model->errors));
        
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $model->attributes = ['text'=>self::$_text, 'name'=>self::$_name];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $model->attributes = ['text'=>'<p><a href="some">'. self::$_text . '</a></p>', 'name'=>'<script src="/my/script.js"></script>' . self::$_name];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        $this->assertEquals(self::$_text, $model->text);
        $this->assertEquals(self::$_name, $model->name);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FOR_UPDATE_CUT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
         $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('active', $model->errors));
        
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FOR_UPDATE_CUT]);
        $model->attributes = ['id'=>self::$_id, 'active'=>self::$_active];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод CommentsModel::setEmails
     */
    public function testSetEmails()
    {
        $model = new CommentsModel();
        
        $this->assertTrue(is_null($model->emails));
        
        $model->emails = new EmailsModel();
        
        $this->assertTrue($model->emails instanceof EmailsModel);
    }
    
    /**
     * Тестирует метод CommentsModel::getEmails
     */
    public function testGetEmails()
    {
        $model = new CommentsModel();
        
        $this->assertTrue(is_null($model->emails));
        
        $model->id_emails = self::$_id;
        
        $this->assertTrue($model->emails instanceof EmailsModel);
    }
    
    /**
     * Тестирует метод CommentsModel::setProducts
     */
    public function testSetProducts()
    {
        $model = new CommentsModel();
        
        $this->assertTrue(is_null($model->products));
        
        $model->products = new ProductsModel();
        
        $this->assertTrue($model->products instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод CommentsModel::getProducts
     */
    public function testGetProducts()
    {
        $model = new CommentsModel();
        
        $this->assertTrue(is_null($model->products));
        
        $model->id_products = self::$_id;
        
        $this->assertTrue($model->products instanceof ProductsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
