<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\CommentsMapper;
use app\models\CommentsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\CommentsMapper
 */
class CommentsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_email = 'tulsa@tulsa.com';
    private static $_text = 'Some Text';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_active = true;
    
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{comments}} SET [[id]]=:id, [[text]]=:text, [[name]]=:name, [[id_emails]]=:id_emails, [[id_products]]=:id_products, [[active]]=:active');
        $command->bindValues([':id'=>self::$_id, ':text'=>self::$_text, ':name'=>self::$_name, ':id_emails'=>self::$_id, ':id_products'=>self::$_id, ':active'=>self::$_active]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод CommentsMapper::getGroup
     */
    public function testGetGroup()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{comments}}')->queryAll()));
        
        $commentsMapper = new CommentsMapper([
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
        ]);
        
        $commentsArray = $commentsMapper->getGroup();
        
        $this->assertTrue(is_array($commentsArray));
        $this->assertFalse(empty($commentsArray));
        $this->assertTrue(is_object($commentsArray[0]));
        $this->assertTrue($commentsArray[0] instanceof CommentsModel);
        
        $this->assertFalse(empty($commentsArray[0]->id));
        $this->assertFalse(empty($commentsArray[0]->text));
        $this->assertFalse(empty($commentsArray[0]->name));
        $this->assertFalse(empty($commentsArray[0]->id_emails));
        $this->assertFalse(empty($commentsArray[0]->id_products));
        $this->assertFalse(empty($commentsArray[0]->active));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
