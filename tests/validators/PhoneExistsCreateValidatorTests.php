<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\PhoneExistsCreateValidator;
use app\tests\DbManager;
use app\models\PhonesModel;

/**
 * Тестирует класс app\validators\PhoneExistsCreateValidator
 */
class PhoneExistsCreateValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_phone = '+380556598754';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'phones'=>'app\tests\sources\fixtures\PhonesFixture',
            ],
        ]);
        
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод PhoneExistsCreateValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->phones['phone_1'];
        
        $model = new PhonesModel();
        $model->phone = $fixture['phone'];
        
        $validator = new PhoneExistsCreateValidator();
        $validator->validateAttribute($model, 'phone');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('phone', $model->errors));
        $this->assertEquals(\Yii::t('base', 'This phone is already registered!'), $model->errors['phone'][0]);
        
        $model = new PhonesModel();
        $model->phone = self::$_phone;
        
        $validator = new PhoneExistsCreateValidator();
        $validator->validateAttribute($model, 'phone');
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует метод PhoneExistsCreateValidator::validate
     */
    public function testValidate()
    {
        $fixture = self::$_dbClass->phones['phone_1'];
        
        $validator = new PhoneExistsCreateValidator();
        $result = $validator->validate($fixture['phone']);
        
        $this->assertTrue($result);
        
        $validator = new PhoneExistsCreateValidator();
        $result = $validator->validate(self::$_phone);
        
        $this->assertFalse($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
