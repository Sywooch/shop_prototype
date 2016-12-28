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
}
