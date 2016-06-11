<?php

namespace app\tests\factories;

use app\factories\CommentsObjectsFactory;
use app\tests\DbManager;
use app\models\CommentsModel;
use app\mappers\CommentsForProductMapper;
use app\queries\CommentsForProductQueryCreator;
use app\models\ProductsModel;

/**
 * Тестирует класс app\factories\CommentsObjectsFactory
 */
class CommentsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод CommentsObjectsFactory::getObjects()
     */
    public function testGetObjects()
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
        
        $this->assertEmpty($commentsForProductMapper->objectsArray);
        $this->assertEmpty($commentsForProductMapper->DbArray);
        
        $commentsForProductMapper->visit(new CommentsForProductQueryCreator());
        
        $command = \Yii::$app->db->createCommand($commentsForProductMapper->query);
        $command->bindValue(':id_products', $commentArray['id_products']);
        $commentsForProductMapper->DbArray = $command->queryAll();
        
        $this->assertFalse(empty($commentsForProductMapper->DbArray));
        
        $commentsForProductMapper->visit(new CommentsObjectsFactory());
        
        $this->assertFalse(empty($commentsForProductMapper->objectsArray));
        $this->assertTrue(is_object($commentsForProductMapper->objectsArray[0]));
        $this->assertTrue($commentsForProductMapper->objectsArray[0] instanceof CommentsModel);
        
        $this->assertTrue(property_exists($commentsForProductMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($commentsForProductMapper->objectsArray[0], 'text'));
        $this->assertTrue(property_exists($commentsForProductMapper->objectsArray[0], 'name'));
        //$this->assertTrue(property_exists($commentsForProductMapper->objectsArray[0], 'id_emails'));
        $this->assertTrue(property_exists($commentsForProductMapper->objectsArray[0], 'id_products'));
        $this->assertTrue(property_exists($commentsForProductMapper->objectsArray[0], 'active'));
        
        $this->assertTrue(isset($commentsForProductMapper->objectsArray[0]->id));
        $this->assertTrue(isset($commentsForProductMapper->objectsArray[0]->text));
        $this->assertTrue(isset($commentsForProductMapper->objectsArray[0]->name));
        $this->assertTrue(isset($commentsForProductMapper->objectsArray[0]->id_emails));
        $this->assertTrue(isset($commentsForProductMapper->objectsArray[0]->id_products));
        $this->assertTrue(isset($commentsForProductMapper->objectsArray[0]->active));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
