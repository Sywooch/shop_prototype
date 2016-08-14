<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\CategorySeocodeExistsValidator;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\validators\CategorySeocodeExistsValidator
 */
class CategorySeocodeExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Обувь';
    private static $_seocode = 'shoes';
    
    private static $_message = 'Этот код уже используется!';
    
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
     * Тестирует метод CategorySeocodeExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_ADD_FORM]);
        $model->seocode = self::$_seocode;
        
        $validator = new CategorySeocodeExistsValidator();
        $validator->validateAttribute($model, 'seocode');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        $this->assertEquals(1, count($model->errors['seocode']));
        $this->assertEquals(self::$_message, $model->errors['seocode'][0]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
