<?php

namespace app\tests\helpers;

use app\tests\DbManager;
use app\helpers\SessionHelper;

/**
 * Тестирует app\helpers\SessionHelper
 */
class SessionHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_session;
    private static $_name = 'sessionname';
    private static $_value = 'sessionvalue';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_session = \Yii::$app->session;
        self::$_session->open();
        self::$_session->set(self::$_name, self::$_value);
        self::$_session->close();
    }
    
    /**
     * Тестирует метод SessionHelper::removeVarFromSession
     */
    public function testRemoveVarFromSession()
    {
        $this->assertTrue(self::$_session->has(self::$_name));
        
        SessionHelper::removeVarFromSession([self::$_name]);
        
        $this->assertFalse(self::$_session->has(self::$_name));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_session->destroy();
        self::$_dbClass->deleteDb();
    }
}
