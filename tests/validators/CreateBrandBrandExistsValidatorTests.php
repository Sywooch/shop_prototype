<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\CreateBrandBrandExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\BrandsFixture;

/**
 * Тестирует класс CreateBrandBrandExistsValidator
 */
class CreateBrandBrandExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new CreateBrandBrandExistsValidator();
    }
    
    /**
     * Тестирует метод CreateBrandBrandExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $brand;
        };
        $reflection = new \ReflectionProperty($model, 'brand');
        $reflection->setValue($model, self::$_dbClass->brands['brand_1']['brand']);
        
        $this->validator->validateAttribute($model, 'brand');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
