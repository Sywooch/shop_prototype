<?php

namespace app\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Object;
use app\validators\ProductTotalProductsValidator;
use app\models\ProductsModel;

/**
 * Тестирует класс app\validators\ProductTotalProductsValidator
 */
class ProductTotalProductsValidatorTests extends TestCase
{
    private static $_totalProducts = '123';
    private static $_totalProducts2 = '123.67';
    private static $_totalProducts3 = 'none';
    private static $_totalProducts4 = '123,67';
    
    /**
     * Тестирует метод ProductTotalProductsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new ProductsModel();
        $model->total_products = self::$_totalProducts3;
        
        $validator = new ProductTotalProductsValidator();
        $validator->validateAttribute($model, 'total_products');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('total_products', $model->errors));
        $this->assertEquals(\Yii::t('base', 'Wrong format!'), $model->errors['total_products'][0]);
        
        $model = new ProductsModel();
        $model->total_products = self::$_totalProducts;
        
        $validator = new ProductTotalProductsValidator();
        $validator->validateAttribute($model, 'total_products');
        
        $expectedInt = 123;
        $this->assertEquals($expectedInt, self::$_totalProducts);
        
        $model = new ProductsModel();
        $model->total_products = self::$_totalProducts2;
        
        $validator = new ProductTotalProductsValidator();
        $validator->validateAttribute($model, 'total_products');
        
        $expectedInt = 123;
        $this->assertEquals($expectedInt, self::$_totalProducts);
        
        $model = new ProductsModel();
        $model->total_products = self::$_totalProducts4;
        
        $validator = new ProductTotalProductsValidator();
        $validator->validateAttribute($model, 'total_products');
        
        $expectedInt = 123;
        $this->assertEquals($expectedInt, self::$_totalProducts);
    }
}
