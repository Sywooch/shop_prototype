<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\CreateSizeSizeExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\SizesFixture;

/**
 * Тестирует класс CreateSizeSizeExistsValidator
 */
class CreateSizeSizeExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>SizesFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new CreateSizeSizeExistsValidator();
    }
    
    /**
     * Тестирует метод CreateSizeSizeExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $size;
        };
        $reflection = new \ReflectionProperty($model, 'size');
        $reflection->setValue($model, self::$_dbClass->sizes['size_1']['size']);
        
        $this->validator->validateAttribute($model, 'size');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
