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
}
