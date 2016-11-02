<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\CityExistsCreateValidator;
use app\tests\DbManager;
use app\models\CitiesModel;

/**
 * Тестирует класс app\validators\CityExistsCreateValidator
 */
class CityExistsCreateValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_city = '221B Baker Street';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'cities'=>'app\tests\sources\fixtures\CitiesFixture',
            ],
        ]);
        
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод CityExistsCreateValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->cities['city_1'];
        
        $model = new CitiesModel();
        $model->city = $fixture['city'];
        
        $validator = new CityExistsCreateValidator();
        $validator->validateAttribute($model, 'city');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('city', $model->errors));
        $this->assertEquals(\Yii::t('base', 'This city is already exists in database!'), $model->errors['city'][0]);
        
        $model = new CitiesModel();
        $model->city = self::$_city;
        
        $validator = new CityExistsCreateValidator();
        $validator->validateAttribute($model, 'city');
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует метод CityExistsCreateValidator::validate
     */
    public function testValidate()
    {
        $fixture = self::$_dbClass->cities['city_1'];
        
        $validator = new CityExistsCreateValidator();
        $result = $validator->validate($fixture['city']);
        
        $this->assertTrue($result);
        
        $validator = new CityExistsCreateValidator();
        $result = $validator->validate(self::$_city);
        
        $this->assertFalse($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
