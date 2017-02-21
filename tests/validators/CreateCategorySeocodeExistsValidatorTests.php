<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\CreateCategorySeocodeExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;

/**
 * Тестирует класс CreateCategorySeocodeExistsValidator
 */
class CreateCategorySeocodeExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new CreateCategorySeocodeExistsValidator();
    }
    
    /**
     * Тестирует метод CreateCategorySeocodeExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $seocode;
        };
        $reflection = new \ReflectionProperty($model, 'seocode');
        $reflection->setValue($model, self::$_dbClass->categories['category_1']['seocode']);
        
        $this->validator->validateAttribute($model, 'seocode');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
