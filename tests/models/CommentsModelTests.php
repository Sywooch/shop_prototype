<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\CommentsModel;

/**
 * Тестирует класс CommentsModel
 */
class CommentsModelTests extends TestCase
{
    /**
     * Тестирует свойства CommentsModel
     */
    public function testProperties()
    {
        $model = new CommentsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('date', $model->attributes);
        $this->assertArrayHasKey('text', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('id_email', $model->attributes);
        $this->assertArrayHasKey('id_product', $model->attributes);
        $this->assertArrayHasKey('active', $model->attributes);
    }
    
    /**
     * Тестирует метод CommentsModel::tableName
     */
    public function testTableName()
    {
        $result = CommentsModel::tableName();
        
        $this->assertSame('comments', $result);
    }
}
