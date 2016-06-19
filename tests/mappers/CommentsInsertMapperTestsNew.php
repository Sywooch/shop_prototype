<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\CommentsInsertMapper;

/**
 * Тестирует класс app\mappers\CommentsInsertMapper
 */
class CommentsInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_email = 'some@some.com';
    private static $_name = 'Some Name';
    private static $_text = 'Some Text';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод CommentsInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $commentsInsertMapper = new CommentsInsertMapper([
            'tableName'=>'comments',
            'fields'=>['text', 'name', 'id_emails', 'id_products'],
            'objectsArray'=>[
                new MockModel([
                    'text'=>self::$_text,
                    'name'=>self::$_name,
                    'id_emails'=>self::$_id,
                    'id_products'=>self::$_id,
                ]),
            ],
        ]);
        
        $result = $commentsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{comments}} WHERE [[comments.name]]=:name');
        $command->bindValue(':name', 'Some Name');
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('id_emails', $result);
        $this->assertArrayHasKey('id_products', $result);
        $this->assertArrayHasKey('active', $result);
        
        //$this->assertEquals(1, $result['id']);
        $this->assertEquals(self::$_text, $result['text']);
        $this->assertEquals(self::$_name, $result['name']);
        $this->assertEquals(self::$_id, $result['id_emails']);
        $this->assertEquals(self::$_id, $result['id_products']);
        $this->assertEquals(0, $result['active']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
