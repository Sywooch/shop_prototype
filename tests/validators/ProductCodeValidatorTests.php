<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\validators\ProductCodeValidator;
use app\models\ProductsModel;

/**
 * Тестирует класс app\validators\ProductCodeValidator
 */
class ProductCodeValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_notExistsCode = '201016ROOT';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products'=>'app\tests\sources\fixtures\ProductsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод ProductCodeValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $model = new ProductsModel();
        $model->code = $fixture['code'];
        
        $validator = new ProductCodeValidator();
        $validator->validateAttribute($model, 'code');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('code', $model->errors));
        $this->assertEquals(\Yii::t('base', 'Product with this code already exists!'), $model->errors['code'][0]);
        
        $model = new ProductsModel();
        $model->code = self::$_notExistsCode;
        
        $validator = new ProductCodeValidator();
        $validator->validateAttribute($model, 'code');
        
        $this->assertTrue(empty($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
