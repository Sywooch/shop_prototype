<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\ColorsColorExistsValidator;
use app\helpers\MappersHelper;
use app\models\ColorsModel;

/**
 * Тестирует класс app\validators\ColorsColorExistsValidator
 */
class ColorsColorExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_color = 'brown';
    
    private static $_message = 'Такой цвет уже добавлен!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ColorsColorExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new ColorsModel();
        $model->color = self::$_color;
        
        $validator = new ColorsColorExistsValidator();
        $validator->validateAttribute($model, 'color');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('color', $model->errors));
        $this->assertEquals(1, count($model->errors['color']));
        $this->assertEquals(self::$_message, $model->errors['color'][0]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
