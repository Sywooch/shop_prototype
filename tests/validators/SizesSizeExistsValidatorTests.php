<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\SizesSizeExistsValidator;
use app\helpers\MappersHelper;
use app\models\SizesModel;

/**
 * Тестирует класс app\validators\SizesSizeExistsValidator
 */
class SizesSizeExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_size = '46';
    
    private static $_message = 'Такой размер уже добавлен!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SizesSizeExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new SizesModel();
        $model->size = self::$_size;
        
        $validator = new SizesSizeExistsValidator();
        $validator->validateAttribute($model, 'size');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('size', $model->errors));
        $this->assertEquals(1, count($model->errors['size']));
        $this->assertEquals(self::$_message, $model->errors['size'][0]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
