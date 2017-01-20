<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\EmailsMailingsModel;

/**
 * Тестирует класс EmailsMailingsModel
 */
class EmailsMailingsModelTests extends TestCase
{
    /**
     * Тестирует свойства EmailsMailingsModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailsMailingsModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('DELETE'));
        
        $model = new EmailsMailingsModel();
        
        $this->assertArrayHasKey('id_email', $model->attributes);
        $this->assertArrayHasKey('id_mailing', $model->attributes);
    }
    
    /**
     * Тестирует метод EmailsMailingsModel::tableName
     */
    public function testTableName()
    {
        $result = EmailsMailingsModel::tableName();
        
        $this->assertSame('emails_mailings', $result);
    }
    
    /**
     * Тестирует метод EmailsMailingsModel::scenarios
     */
    public function testScenarios()
    {
        $model = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::SAVE]);
        $model->attributes = [
            'id_email'=>1,
            'id_mailing'=>2
        ];
        
        $this->assertEquals(1, $model->id_email);
        $this->assertEquals(2, $model->id_mailing);
        
        $model = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::DELETE]);
        $model->attributes = [
            'id_email'=>1,
            'id_mailing'=>2
        ];
        
        $this->assertEquals(1, $model->id_email);
        $this->assertEquals(2, $model->id_mailing);
    }
    
    /**
     * Тестирует метод EmailsMailingsModel::rules
     */
    public function testRules()
    {
        $model = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(2, $model->errors);
        $this->assertArrayHasKey('id_email', $model->errors);
        $this->assertArrayHasKey('id_mailing', $model->errors);
        
        $model = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::SAVE]);
        $model->attributes = [
            'id_email'=>1,
            'id_mailing'=>2
        ];
        
        $this->assertEmpty($model->errors);
        
        $model = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(2, $model->errors);
        $this->assertArrayHasKey('id_email', $model->errors);
        $this->assertArrayHasKey('id_mailing', $model->errors);
        
        $model = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::DELETE]);
        $model->attributes = [
            'id_email'=>1,
            'id_mailing'=>2
        ];
        
        $this->assertEmpty($model->errors);
    }
}
