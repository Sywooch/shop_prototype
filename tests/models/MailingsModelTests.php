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
}
