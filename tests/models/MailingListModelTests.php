<?php

namespace app\tests\models;

use app\models\MailingListModel;

/**
 * Тестирует класс app\models\MailingListModel
 */
class MailingListModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id = 2;
    private static $_name = 'some name';
    private static $_description = 'some description';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\MailingListModel');
    }
    
    /**
     * Тестирует наличие свойств и констант MailingListModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('id'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('name'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('description'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'description'=>self::$_description];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
    }
}
