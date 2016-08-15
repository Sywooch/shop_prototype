<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\SubcategorySeocodeExistsValidator;
use app\helpers\MappersHelper;
use app\models\SubcategoryModel;

/**
 * Тестирует класс app\validators\SubcategorySeocodeExistsValidator
 */
class SubcategorySeocodeExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Обувь';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    private static $_message = 'Этот код уже используется!';
    
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
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SubcategorySeocodeExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_ADD_FORM]);
        $model->seocode = self::$_subcategorySeocode;
        
        $validator = new SubcategorySeocodeExistsValidator();
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
