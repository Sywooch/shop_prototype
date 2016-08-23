<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\ColorsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует ColorsModel
 */
class ColorsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_color = 'gray';
    private static $_color2 = 'green';
    private static $_color3 = 'black';
    private static $_idArray = [12, 5];
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_messageDuplicate = 'Такой цвет уже добавлен!';
    private static $_message = 'С цветом связаны товары! Необходимо перенести их перед удалением!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\ColorsModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id + 1, ':color'=>self::$_color2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id, ':id_colors'=>self::$_id]);
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
        $model = new ColorsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_ADD_PRODUCT'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_ADD'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_UPDATE'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_DELETE'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'color'));
        $this->assertTrue(property_exists($model, 'idArray'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'color'=>self::$_color];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_color, $model->color);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD_PRODUCT]);
        $model->attributes = ['idArray'=>self::$_idArray];
        
        $this->assertEquals(self::$_idArray, $model->idArray);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD]);
        $model->attributes = ['color'=>self::$_color];
        
        $this->assertEquals(self::$_color, $model->color);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'color'=>self::$_color];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_color, $model->color);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_DELETE]);
        $model->attributes = ['id'=>self::$_id, 'color'=>self::$_color];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_color, $model->color);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD_PRODUCT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('idArray', $model->errors));
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD_PRODUCT]);
        $model->attributes = ['idArray'=>self::$_idArray];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('color', $model->errors));
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD]);
        $model->attributes = ['color'=>self::$_color];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('color', $model->errors));
        $this->assertEquals(self::$_messageDuplicate, $model->errors['color'][0]);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD]);
        $model->attributes = ['color'=>self::$_color3];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_UPDATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('color', $model->errors));
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'color'=>self::$_color2];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('color', $model->errors));
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'color'=>self::$_color3];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('color', $model->errors));
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_DELETE]);
        $model->attributes = ['id'=>self::$_id, 'color'=>self::$_color];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('color', $model->errors));
        $this->assertEquals(self::$_message, $model->errors['color'][0]);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_DELETE]);
        $model->attributes = ['id'=>self::$_id + 1, 'color'=>self::$_color2];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
