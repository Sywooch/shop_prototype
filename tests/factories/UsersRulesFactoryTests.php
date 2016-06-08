<?php

namespace app\tests\factories;

use app\factories\UsersRulesFactory;
use app\tests\DbManager;
use app\models\UsersRulesModel;
use app\models\UsersModel;
use app\mappers\UsersRulesInsertMapper;

/**
 * Тестирует класс app\factories\UsersRulesFactory
 */
class UsersRulesFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод UsersRulesFactory::getObjects()
     */
    public function testGetObjects()
    {
        $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $usersModel->attributes = ['login'=>'login1', 'rulesFromForm'=>[1,2]];
        
        $usersRulesInsertMapper = new UsersRulesInsertMapper([
            'tableName'=>'users_rules',
            'fields'=>['id_users', 'id_rules'],
            'model'=>$usersModel
        ]);
        
        $this->assertEmpty($usersRulesInsertMapper->objectsArray);
        $this->assertEmpty($usersRulesInsertMapper->DbArray);
        
        $usersRulesInsertMapper->DbArray = [['id_users'=>1,'id_rules'=>1], ['id_users'=>2,'id_rules'=>2], ['id_users'=>3,'id_rules'=>3]];
        
        $usersRulesInsertMapper->visit(new UsersRulesFactory());
        
        $this->assertFalse(empty($usersRulesInsertMapper->DbArray));
        
        $this->assertFalse(empty($usersRulesInsertMapper->objectsArray));
        $this->assertTrue(is_object($usersRulesInsertMapper->objectsArray[0]));
        $this->assertTrue($usersRulesInsertMapper->objectsArray[0] instanceof UsersRulesModel);
        
        $this->assertTrue(property_exists($usersRulesInsertMapper->objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($usersRulesInsertMapper->objectsArray[0], 'id_rules'));
        
        $this->assertTrue(isset($usersRulesInsertMapper->objectsArray[0]->id_users));
        $this->assertTrue(isset($usersRulesInsertMapper->objectsArray[0]->id_rules));
    }
}
