<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\CreateCategoryNameExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;

/**
 * Тестирует класс CreateCategoryNameExistsValidator
 */
class CreateCategoryNameExistsValidatorTests extends TestCase
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
        
        $this->validator = new CreateCategoryNameExistsValidator();
    }
    
    /**
     * Тестирует метод CreateCategoryNameExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $name;
        };
        $reflection = new \ReflectionProperty($model, 'name');
        $reflection->setValue($model, self::$_dbClass->categories['category_1']['name']);
        
        $this->validator->validateAttribute($model, 'name');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
