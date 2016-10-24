<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Object;
use app\validators\ProductPriceValidator;
use app\models\ProductsModel;

/**
 * Тестирует класс app\validators\ProductPriceValidator
 */
class ProductPriceValidatorTests extends TestCase
{
    private static $_price = '132,45';
    private static $_expectedPrice = '132.45';
    private static $_price2 = '12895.4';
    private static $_expectedPrice2 = '12895.40';
    private static $_price3 = '25 854.1';
    private static $_expectedPrice3 = '25854.10';
    private static $_nonePrice = 'none';
    
    /**
     * Тестирует метод ProductPriceValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new ProductsModel();
        $model->price = self::$_nonePrice;
        
        $validator = new ProductPriceValidator();
        $validator->validateAttribute($model, 'price');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('price', $model->errors));
        $this->assertEquals(\Yii::t('base/errors', 'Wrong format!'), $model->errors['price'][0]);
        
        $model = new ProductsModel();
        $model->price = self::$_price;
        
        $validator = new ProductPriceValidator();
        $validator->validateAttribute($model, 'price');
        
        $this->assertEquals(self::$_expectedPrice, $model->price);
        
        $model = new ProductsModel();
        $model->price = self::$_price2;
        
        $validator = new ProductPriceValidator();
        $validator->validateAttribute($model, 'price');
        
        $this->assertEquals(self::$_expectedPrice2, $model->price);
        
        $model = new ProductsModel();
        $model->price = self::$_price3;
        
        $validator = new ProductPriceValidator();
        $validator->validateAttribute($model, 'price');
        
        $this->assertEquals(self::$_expectedPrice3, $model->price);
    }
}
