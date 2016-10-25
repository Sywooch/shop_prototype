<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Object;
use app\tests\DbManager;
use app\validators\ProductSeocodeValidator;
use app\models\ProductsModel;

/**
 * Тестирует класс app\validators\ProductSeocodeValidator
 */
class ProductSeocodeValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_name = 'Коричневый шерстяной шарф';
    private static $_expectedSeocode = 'korichnevyi-sherstyanoi-sharf';
    private static $_notExistsSeocode = 'full-blood-moon';
    
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
     * Тестирует метод ProductSeocodeValidator::validateAttribute
     */
     public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $model = new ProductsModel();
        $model->seocode = $fixture['seocode'];
        
        $validator = new ProductSeocodeValidator();
        $validator->validateAttribute($model, 'seocode');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('seocode', $model->errors));
        $this->assertEquals(\Yii::t('base', 'Product with this seocode already exists!'), $model->errors['seocode'][0]);
        
        $model = new ProductsModel();
        $model->seocode = self::$_notExistsSeocode;
        
        $validator = new ProductSeocodeValidator();
        $validator->validateAttribute($model, 'seocode');
        
        $this->assertTrue(empty($model->errors));
        
        $model = new ProductsModel();
        $model->name = self::$_name;
        
        $validator = new ProductSeocodeValidator();
        $validator->validateAttribute($model, 'seocode');
        
        $this->assertEquals(self::$_expectedSeocode, $model->seocode);
        
        $model = new ProductsModel();
        $model->name = $fixture['name'];
        $model->code = $fixture['code'];
        
        $validator = new ProductSeocodeValidator();
        $validator->validateAttribute($model, 'seocode');
        
        $this->assertEquals($fixture['seocode'] . '-' . $fixture['code'], $model->seocode);
    }
    
    public static function tearDownAfterCLass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
