<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\CreateColorColorExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\ColorsFixture;

/**
 * Тестирует класс CreateColorColorExistsValidator
 */
class CreateColorColorExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new CreateColorColorExistsValidator();
    }
    
    /**
     * Тестирует метод CreateColorColorExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $color;
        };
        $reflection = new \ReflectionProperty($model, 'color');
        $reflection->setValue($model, self::$_dbClass->colors['color_1']['color']);
        
        $this->validator->validateAttribute($model, 'color');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
