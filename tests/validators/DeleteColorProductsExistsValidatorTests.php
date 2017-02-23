<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\DeleteColorProductsExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ProductsColorsFixture,
    ProductsFixture};

/**
 * Тестирует класс DeleteColorProductsExistsValidator
 */
class DeleteColorProductsExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new DeleteColorProductsExistsValidator();
    }
    
    /**
     * Тестирует метод DeleteColorProductsExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $id = 1;
        };
        
        $this->validator->validateAttribute($model, 'id');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
