<?php

namespace app\test\models;

use app\models\SearchModel;

/**
 * Тестирует SearchModel
 */
class SearchModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_search = 'hello world!';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\SearchModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new SearchModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('search'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new SearchModel(['search'=>SearchModel::GET_FROM_FORM]);
        $model->attributes = ['search'=>self::$_search];
        
        $this->assertFalse(empty($model->search));
    }
}
