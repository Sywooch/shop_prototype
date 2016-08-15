<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\CategoriesForeignProductsExistsValidator;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\validators\CategoriesForeignProductsExistsValidator
 */
class CategoriesForeignProductsExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Обувь';
    private static $_seocode = 'shoes';
    private static $_subcategorySeocode = 'sneakers';
    
    private static $_message = 'С категорией связаны товары! Необходимо перенести их перед удалением!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_seocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод CategoriesForeignProductsExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_DELETE_FORM]);
        $model->id = self::$_id;
        $model->name = self::$_name;
        
        $validator = new CategoriesForeignProductsExistsValidator();
        $validator->validateAttribute($model, 'name');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertEquals(1, count($model->errors['name']));
        $this->assertEquals(self::$_message, $model->errors['name'][0]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
