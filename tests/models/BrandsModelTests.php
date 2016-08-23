<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\BrandsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует BrandsModel
 */
class BrandsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_brand = 'Night relax';
    private static $_brand2 = 'Early relax';
    private static $_brand3 = 'Morning Star';
    private static $_message = 'С брендом связаны товары! Необходимо перенести их перед удалением!';
    private static $_messageDuplicate = 'Бренд с таким именем уже добавлен!';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\BrandsModel');
        
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id + 1, ':brand'=>self::$_brand2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_brands}} SET [[id_products]]=:id_products, [[id_brands]]=:id_brands');
        $command->bindValues([':id_products'=>self::$_id, ':id_brands'=>self::$_id]);
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
        $model = new BrandsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_ADD_PRODUCT'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_ADD'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_DELETE'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_UPDATE'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'brand'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'brand'=>self::$_brand];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->brand));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD_PRODUCT]);
        $model->attributes = ['id'=>self::$_id];
        
        $this->assertFalse(empty($model->id));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD]);
        $model->attributes = ['brand'=>self::$_brand];
        
        $this->assertFalse(empty($model->brand));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_DELETE]);
        $model->attributes = ['id'=>self::$_id, 'brand'=>self::$_brand];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->brand));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'brand'=>self::$_brand];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->brand));
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD_PRODUCT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD_PRODUCT]);
        $model->attributes = ['id'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('brand', $model->errors));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD]);
        $model->attributes = ['brand'=>self::$_brand2];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('brand', $model->errors));
        $this->assertEquals(self::$_messageDuplicate, $model->errors['brand'][0]);
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD]);
        $model->attributes = ['brand'=>self::$_brand3];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('brand', $model->errors));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_DELETE]);
        $model->attributes = ['id'=>self::$_id, 'brand'=>self::$_brand];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('brand', $model->errors));
        $this->assertEquals(self::$_message, $model->errors['brand'][0]);
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_DELETE]);
        $model->attributes = ['id'=>self::$_id + 1, 'brand'=>self::$_brand2];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_UPDATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('brand', $model->errors));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'brand'=>self::$_brand2];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('brand', $model->errors));
        $this->assertEquals(self::$_messageDuplicate, $model->errors['brand'][0]);
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'brand'=>self::$_brand3];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
