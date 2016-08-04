<?php

namespace app\tests\models;

use app\models\EmailsMailingListModel;

/**
 * Тестирует класс app\models\EmailsMailingListModel
 */
class EmailsMailingListModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id_email = 23;
    private static $_id_mailing_list = 12;
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\EmailsMailingListModel');
    }
    
    /**
     * Тестирует наличие свойств и констант EmailsMailingListModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('id_email'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('id_mailing_list'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new EmailsMailingListModel(['scenario'=>EmailsMailingListModel::GET_FROM_DB]);
        $model->attributes = ['id_email'=>self::$_id_email, 'id_mailing_list'=>self::$_id_mailing_list];
        
        $this->assertFalse(empty($model->id_email));
        $this->assertFalse(empty($model->id_mailing_list));
    }
}
