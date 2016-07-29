<?php

namespace app\tests\mappers;

use app\tests\{DbManager, MockModel};
use app\mappers\CommentsForProductMapper;
use app\models\{CommentsModel, ProductsModel};
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\CommentsForProductMapper
 */
class CommentsForProductMapperTests extends \PHPUnit_Framework_TestCase
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{comments}} SET [[id]]=:id, [[text]]=:text, [[name]]=:name, [[id_emails]]=:id_emails, [[id_products]]=:id_products, [[active]]=:active');
        $command->bindValues([':id'=>self::$_id, ':text'=>self::$_text, ':name'=>self::$_name, ':id_emails'=>self::$_id, ':id_products'=>self::$_id, ':active'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод CommentsForProductMapper::getGroup
     */
    public function testGetGroup()
    {
        $commentsForProductMapper = new CommentsForProductMapper([
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
            'model'=>new ProductsModel([
                'id'=>self::$_id,
            ]),
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
        self::$_dbClass->deleteDb();
    }
}
