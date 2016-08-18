<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\BrandsForeignProductsExistsValidator;
use app\helpers\MappersHelper;
use app\models\BrandsModel;

/**
 * Тестирует класс app\validators\BrandsForeignProductsExistsValidator
 */
class BrandsForeignProductsExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
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
    
    private static $_message = 'С брендом связаны товары! Необходимо перенести их перед удалением!';
    
    public static function setUpBeforeClass()
    {
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_brands}} SET [[id_products]]=:id_products, [[id_brands]]=:id_brands');
        $command->bindValues([':id_products'=>self::$_id, ':id_brands'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод BrandsForeignProductsExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_DELETE_FORM]);
        $model->id = self::$_id;
        $model->brand = self::$_brand;
        
        $validator = new BrandsForeignProductsExistsValidator();
        $validator->validateAttribute($model, 'brand');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('brand', $model->errors));
        $this->assertEquals(1, count($model->errors['brand']));
        $this->assertEquals(self::$_message, $model->errors['brand'][0]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
