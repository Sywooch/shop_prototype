<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\PostcodeExistsCreateValidator;
use app\tests\DbManager;
use app\models\PostcodesModel;

/**
 * Тестирует класс app\validators\PostcodeExistsCreateValidator
 */
class PostcodeExistsCreateValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_postcode = 'Columbia';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'postcodes'=>'app\tests\sources\fixtures\PostcodesFixture',
            ],
        ]);
        
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод PostcodeExistsCreateValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->postcodes['postcode_1'];
        
        $model = new PostcodesModel();
        $model->postcode = $fixture['postcode'];
        
        $validator = new PostcodeExistsCreateValidator();
        $validator->validateAttribute($model, 'postcode');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('postcode', $model->errors));
        $this->assertEquals(\Yii::t('base', 'This postcode is already exists in database!'), $model->errors['postcode'][0]);
        
        $model = new PostcodesModel();
        $model->postcode = self::$_postcode;
        
        $validator = new PostcodeExistsCreateValidator();
        $validator->validateAttribute($model, 'postcode');
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует метод PostcodeExistsCreateValidator::validate
     */
    public function testValidate()
    {
        $fixture = self::$_dbClass->postcodes['postcode_1'];
        
        $validator = new PostcodeExistsCreateValidator();
        $result = $validator->validate($fixture['postcode']);
        
        $this->assertTrue($result);
        
        $validator = new PostcodeExistsCreateValidator();
        $result = $validator->validate(self::$_postcode);
        
        $this->assertFalse($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
