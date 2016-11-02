<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\SurnameExistsCreateValidator;
use app\tests\DbManager;
use app\models\SurnamesModel;

/**
 * Тестирует класс app\validators\SurnameExistsCreateValidator
 */
class SurnameExistsCreateValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_surname = 'Rambo';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'surnames'=>'app\tests\sources\fixtures\SurnamesFixture',
            ],
        ]);
        
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод SurnameExistsCreateValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->surnames['surname_1'];
        
        $model = new SurnamesModel();
        $model->surname = $fixture['surname'];
        
        $validator = new SurnameExistsCreateValidator();
        $validator->validateAttribute($model, 'surname');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('surname', $model->errors));
        $this->assertEquals(\Yii::t('base', 'This surname is already exists in database!'), $model->errors['surname'][0]);
        
        $model = new SurnamesModel();
        $model->surname = self::$_surname;
        
        $validator = new SurnameExistsCreateValidator();
        $validator->validateAttribute($model, 'surname');
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует метод SurnameExistsCreateValidator::validate
     */
    public function testValidate()
    {
        $fixture = self::$_dbClass->surnames['surname_1'];
        
        $validator = new SurnameExistsCreateValidator();
        $result = $validator->validate($fixture['surname']);
        
        $this->assertTrue($result);
        
        $validator = new SurnameExistsCreateValidator();
        $result = $validator->validate(self::$_surname);
        
        $this->assertFalse($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
