<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\AddressExistsCreateValidator;
use app\tests\DbManager;
use app\models\AddressModel;

/**
 * Тестирует класс app\validators\AddressExistsCreateValidator
 */
class AddressExistsCreateValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_address = '221B Baker Street';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'address'=>'app\tests\sources\fixtures\AddressFixture',
            ],
        ]);
        
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод AddressExistsCreateValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->address['address_1'];
        
        $model = new AddressModel();
        $model->address = $fixture['address'];
        
        $validator = new AddressExistsCreateValidator();
        $validator->validateAttribute($model, 'address');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('address', $model->errors));
        $this->assertEquals(\Yii::t('base', 'This address is already exists in database!'), $model->errors['address'][0]);
        
        $model = new AddressModel();
        $model->address = self::$_address;
        
        $validator = new AddressExistsCreateValidator();
        $validator->validateAttribute($model, 'address');
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует метод AddressExistsCreateValidator::validate
     */
    public function testValidate()
    {
        $fixture = self::$_dbClass->address['address_1'];
        
        $validator = new AddressExistsCreateValidator();
        $result = $validator->validate($fixture['address']);
        
        $this->assertTrue($result);
        
        $validator = new AddressExistsCreateValidator();
        $result = $validator->validate(self::$_address);
        
        $this->assertFalse($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
