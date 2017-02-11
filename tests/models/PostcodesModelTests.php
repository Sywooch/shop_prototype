<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\PostcodesModel;

/**
 * Тестирует класс PostcodesModel
 */
class PostcodesModelTests extends TestCase
{
    /**
     * Тестирует свойства PostcodesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PostcodesModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $model = new PostcodesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('postcode', $model->attributes);
    }
    
    /**
     * Тестирует метод PostcodesModel::tableName
     */
    public function testTableName()
    {
        $result = PostcodesModel::tableName();
        
        $this->assertSame('postcodes', $result);
    }
    
    /**
     * Тестирует метод PostcodesModel::rules
     */
    public function testRules()
    {
        $model = new PostcodesModel(['scenario'=>PostcodesModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        
        $model = new PostcodesModel(['scenario'=>PostcodesModel::SAVE]);
        $model->attributes = [
            'postcode'=>'postcode'
        ];
        
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
