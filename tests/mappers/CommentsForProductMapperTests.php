<?php

namespace app\tests\mappers;

use app\mappers\CommentsForProductMapper;
use app\tests\DbManager;
use app\models\CommentsModel;
use app\models\ProductsModel;

/**
 * Тестирует класс app\mappers\CommentsForProductMapper
 */
class CommentsForProductMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод CommentsForProductMapper::getGroup
     */
    public function testGetGroup()
    {
        $commentArray = ['text'=>'Some text', 'name'=>'Some Name', 'id_emails'=>1, 'id_products'=>1];
        $command = \Yii::$app->db->createCommand('INSERT INTO {{comments}} SET [[text]]=:text, [[name]]=:name, [[id_emails]]=:id_emails, [[id_products]]=:id_products');
        $command->bindValues([':text'=>$commentArray['text'], ':name'=>$commentArray['name'], ':id_emails'=>$commentArray['id_emails'], ':id_products'=>$commentArray['id_products']]);
        $command->execute();
        
        $commentsForProductMapper = new CommentsForProductMapper([
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
            'model'=>new ProductsModel(['id'=>1]),
        ]);
        $commentsList = $commentsForProductMapper->getGroup();
        
        $this->assertTrue(is_array($commentsList));
        $this->assertFalse(empty($commentsList));
        $this->assertTrue(is_object($commentsList[0]));
        $this->assertTrue($commentsList[0] instanceof CommentsModel);
        
        $this->assertTrue(property_exists($commentsList[0], 'id'));
        $this->assertTrue(property_exists($commentsList[0], 'text'));
        $this->assertTrue(property_exists($commentsList[0], 'name'));
        //$this->assertTrue(property_exists($commentsList[0], 'id_emails'));
        $this->assertTrue(property_exists($commentsList[0], 'id_products'));
        $this->assertTrue(property_exists($commentsList[0], 'active'));
        
        $this->assertTrue(isset($commentsList[0]->id));
        $this->assertTrue(isset($commentsList[0]->text));
        $this->assertTrue(isset($commentsList[0]->name));
        $this->assertTrue(isset($commentsList[0]->id_emails));
        $this->assertTrue(isset($commentsList[0]->id_products));
        $this->assertTrue(isset($commentsList[0]->active));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
