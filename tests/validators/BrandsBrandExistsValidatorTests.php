<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\BrandsBrandExistsValidator;
use app\helpers\MappersHelper;
use app\models\BrandsModel;

/**
 * Тестирует класс app\validators\BrandsBrandExistsValidator
 */
class BrandsBrandExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_brand = 'Corke';
    
    private static $_message = 'Бренд с таким именем уже добавлен!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод BrandsBrandExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_UPDATE_FORM]);
        $model->brand = self::$_brand;
        
        $validator = new BrandsBrandExistsValidator();
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
