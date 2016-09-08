<?php

namespace app\tests\helpers;

use app\tests\DbManager;
use app\helpers\InstancesHelper;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\helpers\InstancesHelper
 */
class InstancesHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'name';
    private static $_categorySeocode = 'mensfootwear';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
    }
    
    /**
     * Тестирует метод InstancesHelper::getInstances
     */
    public function testGetInstances()
    {
        $result = InstancesHelper::getInstances();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertTrue(array_key_exists('categoriesList', $result));
        $this->assertTrue(is_array($result['categoriesList']));
        $this->assertTrue($result['categoriesList'][0] instanceof CategoriesModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
