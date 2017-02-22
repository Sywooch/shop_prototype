<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\CreateSubcategorySeocodeExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;

/**
 * Тестирует класс CreateSubcategorySeocodeExistsValidator
 */
class CreateSubcategorySeocodeExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new CreateSubcategorySeocodeExistsValidator();
    }
    
    /**
     * Тестирует метод CreateSubcategorySeocodeExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $seocode;
        };
        $reflection = new \ReflectionProperty($model, 'seocode');
        $reflection->setValue($model, self::$_dbClass->subcategory['subcategory_1']['seocode']);
        
        $this->validator->validateAttribute($model, 'seocode');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
