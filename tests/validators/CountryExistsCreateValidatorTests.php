<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\CountryExistsCreateValidator;
use app\tests\DbManager;
use app\models\CountriesModel;

/**
 * Тестирует класс app\validators\CountryExistsCreateValidator
 */
class CountryExistsCreateValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_country = 'Columbia';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'countries'=>'app\tests\sources\fixtures\CountriesFixture',
            ],
        ]);
        
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод CountryExistsCreateValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->countries['country_1'];
        
        $model = new CountriesModel();
        $model->country = $fixture['country'];
        
        $validator = new CountryExistsCreateValidator();
        $validator->validateAttribute($model, 'country');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('country', $model->errors));
        $this->assertEquals(\Yii::t('base', 'This country is already exists in database!'), $model->errors['country'][0]);
        
        $model = new CountriesModel();
        $model->country = self::$_country;
        
        $validator = new CountryExistsCreateValidator();
        $validator->validateAttribute($model, 'country');
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует метод CountryExistsCreateValidator::validate
     */
    public function testValidate()
    {
        $fixture = self::$_dbClass->countries['country_1'];
        
        $validator = new CountryExistsCreateValidator();
        $result = $validator->validate($fixture['country']);
        
        $this->assertTrue($result);
        
        $validator = new CountryExistsCreateValidator();
        $result = $validator->validate(self::$_country);
        
        $this->assertFalse($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
