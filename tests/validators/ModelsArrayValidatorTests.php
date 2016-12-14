<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\ModelsArrayValidator;
use yii\base\Model;

/**
 * Тестирует класс ModelsArrayValidator
 */
class ModelsArrayValidatorTests extends TestCase
{
    private $model;
    
    public function setUp()
    {
        $this->model = new class() extends Model {
            public $items = [];
        };
    }
    
    /**
    * Тестирует метод ModelsArrayValidator::validateAttribute
    */
    public function testValidateAttributeError()
    {
        $object = new class() extends Model {};
        
        $reflection = new \ReflectionProperty($this->model, 'items');
        $reflection->setValue($this->model, [$object, [1]]);
        
        $validator = new ModelsArrayValidator();
        $validator->validateAttribute($this->model, 'items');
        
        $this->assertNotEmpty($this->model->errors);
        $this->assertCount(1, $this->model->errors);
        $this->assertArrayHasKey('items', $this->model->errors);
    }
    
    /**
    * Тестирует метод ModelsArrayValidator::validateAttribute
    */
    public function testValidateAttribute()
    {
        $object = new class() extends Model {};
        
        $reflection = new \ReflectionProperty($this->model, 'items');
        $reflection->setValue($this->model, [$object]);
        
        $validator = new ModelsArrayValidator();
        $validator->validateAttribute($this->model, 'items');
        
        $this->assertEmpty($this->model->errors);
    }
}
