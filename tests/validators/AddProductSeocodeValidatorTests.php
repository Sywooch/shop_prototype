<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\AddProductSeocodeValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

/**
 * Тестирует класс AddProductSeocodeValidator
 */
class AddProductSeocodeValidatorTests extends TestCase
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
        
        $this->validator = new AddProductSeocodeValidator();
    }
    
    /**
     * Тестирует метод AddProductSeocodeValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $oldSeocode = self::$_dbClass->products['product_1']['seocode'];
        
        $model = new class() extends Model {
            public $seocode;
            public $code = 'NEWCODE1';
        };
        
        $reflection = new \ReflectionProperty($model, 'seocode');
        $reflection->setValue($model, $oldSeocode);
        
        $this->validator->validateAttribute($model, 'seocode');
        
        $this->assertNotEquals($oldSeocode, $model->seocode);
        $this->assertEquals(implode('-', [self::$_dbClass->products['product_1']['seocode'], mb_strtolower('NEWCODE1', 'UTF-8')]), $model->seocode);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
