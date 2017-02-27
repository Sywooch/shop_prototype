<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\EditProductCodeExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

/**
 * Тестирует класс EditProductCodeExistsValidator
 */
class EditProductCodeExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new EditProductCodeExistsValidator();
    }
    
    /**
     * Тестирует метод EditProductCodeExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $id;
            public $code;
        };
        $reflection = new \ReflectionProperty($model, 'id');
        $reflection->setValue($model, self::$_dbClass->products['product_1']['id']);
        $reflection = new \ReflectionProperty($model, 'code');
        $reflection->setValue($model, self::$_dbClass->products['product_1']['code']);
        
        $this->validator->validateAttribute($model, 'code');
        
        $this->assertEmpty($model->errors);
        
        $model = new class() extends Model {
            public $id;
            public $code;
        };
        $reflection = new \ReflectionProperty($model, 'id');
        $reflection->setValue($model, self::$_dbClass->products['product_2']['id']);
        $reflection = new \ReflectionProperty($model, 'code');
        $reflection->setValue($model, self::$_dbClass->products['product_1']['code']);
        
        $this->validator->validateAttribute($model, 'code');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
