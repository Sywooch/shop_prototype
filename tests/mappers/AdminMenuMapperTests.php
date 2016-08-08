<?php

namespace app\tests\some;

use app\tests\DbManager;
use app\mappers\AdminMenuMapper;
use app\models\AdminMenuModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\AdminMenuMapper
 */
class AdminMenuMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_route = 'some/index';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{admin_menu}} SET [[id]]=:id, [[name]]=:name, [[route]]=:route');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name,  ':route'=>self::$_route]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод AdminMenuMapper::getGroup
     */
    public function testGetGroup()
    {
        $adminMenuMapper = new AdminMenuMapper([
            'tableName'=>'admin_menu',
            'fields'=>['id', 'name', 'route'],
        ]);
        $adminMenuList = $adminMenuMapper->getGroup();
        
        $this->assertTrue(is_array($adminMenuList));
        $this->assertFalse(empty($adminMenuList));
        $this->assertTrue(is_object($adminMenuList[0]));
        $this->assertTrue($adminMenuList[0] instanceof AdminMenuModel);
        
        $this->assertTrue(property_exists($adminMenuList[0], 'id'));
        $this->assertTrue(property_exists($adminMenuList[0], 'name'));
        $this->assertTrue(property_exists($adminMenuList[0], 'route'));
        
        $this->assertFalse(empty($adminMenuList[0]->id));
        $this->assertFalse(empty($adminMenuList[0]->name));
        $this->assertFalse(empty($adminMenuList[0]->route));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
