<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\ProductsSizesDeleteMapper;
use app\helpers\MappersHelper;
use app\models\ProductsSizesModel;

/**
 * Тестирует класс app\mappers\ProductsSizesDeleteMapper
 */
class ProductsSizesDeleteMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 3;
    private static $_size = '46';
    private static $_name = 'Some Name';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ProductsSizesDeleteMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll()));
        
        $productsSizesDeleteMapper = new ProductsSizesDeleteMapper([
            'tableName'=>'products_sizes',
            'objectsArray'=>[
                new ProductsSizesModel(['id_products'=>self::$_id]),
            ],
        ]);
        
        $result = $productsSizesDeleteMapper->setGroup();
        
        $this->assertEquals(1, $result);
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll()));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
