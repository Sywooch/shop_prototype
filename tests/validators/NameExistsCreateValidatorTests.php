<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\NameExistsCreateValidator;
use app\tests\DbManager;
use app\models\NamesModel;

/**
 * Тестирует класс app\validators\NameExistsCreateValidator
 */
class NameExistsCreateValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_name = 'Freddi';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'names'=>'app\tests\sources\fixtures\NamesFixture',
            ],
        ]);
        
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод NameExistsCreateValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->names['name_1'];
        
        $model = new NamesModel();
        $model->name = $fixture['name'];
        
        $validator = new NameExistsCreateValidator();
        $validator->validateAttribute($model, 'name');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertEquals(\Yii::t('base', 'This name is already exists in database!'), $model->errors['name'][0]);
        
        $model = new NamesModel();
        $model->name = self::$_name;
        
        $validator = new NameExistsCreateValidator();
        $validator->validateAttribute($model, 'name');
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод NameExistsCreateValidator::validate
     */
    public function testValidate()
    {
        $fixture = self::$_dbClass->names['name_1'];
        
        $validator = new NameExistsCreateValidator();
        $result = $validator->validate($fixture['name']);
        
        $this->assertTrue($result);
        
        $validator = new NameExistsCreateValidator();
        $result = $validator->validate(self::$_name);
        
        $this->assertFalse($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
