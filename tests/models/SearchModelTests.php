<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\SearchModel;

/**
 * Тестирует класс app\models\SearchModel
 */
class SearchModelTests extends TestCase
{
    private static $_reflectionClass;
    private static $_text = 'Adidas';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('\app\models\SearchModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\SearchModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('text'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new SearchModel(['scenario'=>SearchModel::GET_FROM_FORM]);
        $model->attributes = [
            'text'=>self::$_text
        ];
        
        $this->assertEquals(self::$_text, $model->text);
    }
}
