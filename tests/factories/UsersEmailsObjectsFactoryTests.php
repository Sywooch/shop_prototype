<?php

namespace app\tests\factories;

use app\tests\DbManager;
use app\factories\UsersEmailsObjectsFactory;
use app\models\UsersEmailsModel;
use app\mappers\UsersEmailsInsertMapper;

/**
 * Тестирует класс app\factories\UsersEmailsObjectsFactory
 */
class UsersEmailsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод UsersEmailsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $usersEmailsInsertMapper = new UsersEmailsInsertMapper([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
            'DbArray'=>[['id_users'=>1, 'id_emails'=>1]],
        ]);
        
        $this->assertEmpty($usersEmailsInsertMapper->objectsArray);
        
        $usersEmailsInsertMapper->visit(new UsersEmailsObjectsFactory());
        
        $this->assertFalse(empty($usersEmailsInsertMapper->objectsArray));
        $this->assertTrue(is_object($usersEmailsInsertMapper->objectsArray[0]));
        $this->assertTrue($usersEmailsInsertMapper->objectsArray[0] instanceof UsersEmailsModel);
        
        $this->assertTrue(property_exists($usersEmailsInsertMapper->objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($usersEmailsInsertMapper->objectsArray[0], 'id_emails'));
        
        $this->assertTrue(isset($usersEmailsInsertMapper->objectsArray[0]->id_users));
        $this->assertTrue(isset($usersEmailsInsertMapper->objectsArray[0]->id_emails));
    }
}
