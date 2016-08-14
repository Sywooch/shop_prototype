<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\CategoryNameExistsValidator;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\validators\CategoryNameExistsValidator
 */
class CategoryNameExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Обувь';
    private static $_seocode = 'shoes';
    
    private static $_message = 'Категория уже существует!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_seocode]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод CategoryNameExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_ADD_FORM]);
        $model->name = self::$_name;
        
        $validator = new CategoryNameExistsValidator();
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
