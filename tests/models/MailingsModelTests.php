<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\MailingsModel;

/**
 * Тестирует класс MailingsModel
 */
class MailingsModelTests extends TestCase
{
    /**
     * Тестирует свойства MailingsModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsModel::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        $this->assertTrue($reflection->hasConstant('EDIT'));
        
        $model = new MailingsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('description', $model->attributes);
    }
    
    /**
     * Тестирует метод MailingsModel::tableName
     */
    public function testTableName()
    {
        $result = MailingsModel::tableName();
        
        $this->assertSame('mailings', $result);
    }
    
    /**
     * Тестирует метод MailingsModel::scenarios
     */
    public function testScenarios()
    {
        $model = new MailingsModel(['scenario'=>MailingsModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        
        $this->assertEquals(23, $model->id);
        
        $model = new MailingsModel(['scenario'=>MailingsModel::CREATE]);
        $model->attributes = [
            'name'=>'name',
            'description'=>'description',
            'active'=>1
        ];
        
        $this->assertEquals('name', $model->name);
        $this->assertEquals('description', $model->description);
        $this->assertEquals(1, $model->active);
        
        $model = new MailingsModel(['scenario'=>MailingsModel::EDIT]);
        $model->attributes = [
            'id'=>45,
            'name'=>'name',
            'description'=>'description',
            'active'=>1
        ];
        
        $this->assertEquals(45, $model->id);
        $this->assertEquals('name', $model->name);
        $this->assertEquals('description', $model->description);
        $this->assertEquals(1, $model->active);
    }
    
    /**
     * Тестирует метод MailingsModel::rules
     */
    public function testRules()
    {
        $model = new MailingsModel(['scenario'=>MailingsModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new MailingsModel(['scenario'=>MailingsModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new MailingsModel(['scenario'=>MailingsModel::CREATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(2, $model->errors);
        
        $model = new MailingsModel(['scenario'=>MailingsModel::CREATE]);
        $model->attributes = [
            'name'=>'name',
            'description'=>'description',
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertSame(0, $model->active);
        
        $model = new MailingsModel(['scenario'=>MailingsModel::EDIT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(3, $model->errors);
        
        $model = new MailingsModel(['scenario'=>MailingsModel::EDIT]);
        $model->attributes = [
            'id'=>45,
            'name'=>'name',
            'description'=>'description',
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
