<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\EmailsModel;

/**
 * Тестирует класс EmailsModel
 */
class EmailsModelTests extends TestCase
{
    /**
     * Тестирует свойства EmailsModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailsModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $model = new EmailsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('email', $model->attributes);
    }
    
    /**
     * Тестирует метод EmailsModel::tableName
     */
    public function testTableName()
    {
        $result = EmailsModel::tableName();
        
        $this->assertSame('emails', $result);
    }
    
    /**
     * Тестирует метод EmailsModel::rules
     */
    public function testRules()
    {
        $model = new EmailsModel(['scenario'=>EmailsModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        
        $model = new EmailsModel(['scenario'=>EmailsModel::SAVE]);
        $model->attributes = [
            'email'=>'email'
        ];
        
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
