<?php

namespace app\tests\mappers;

use app\tests\{DbManager, MockModel};
use app\mappers\SimilarProductsMapper;
use app\models\ProductsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SimilarProductsMapper
 */
class SimilarProductsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_color = 'gray';
    private static $_size = '46';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id + 1, ':date'=>self::$_date, ':code'=>self::$_code . 'n', ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id, ':id_colors'=>self::$_id]);
        $command->execute();
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id + 1, ':id_colors'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id, ':id_sizes'=>self::$_id]);
        $command->execute();
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id + 1, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SimilarProductsMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = ['id'=>self::$_id, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode];
        
        $similarProductsMapper = new SimilarProductsMapper([
            'tableName'=>'products',
            'fields'=>['id', 'name', 'price', 'images'],
            'otherTablesFields'=>[
                ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
            ],
            'orderByField'=>'date',
            'model'=>new ProductsModel([
                'id'=>self::$_id,
            ]),
        ]);
        $productsList = $similarProductsMapper->getGroup();
        
        $this->assertTrue(is_array($productsList));
        $this->assertFalse(empty($productsList));
        $this->assertTrue(is_object($productsList[0]));
        $this->assertTrue($productsList[0] instanceof ProductsModel);
        
        //$this->assertTrue(property_exists($productsList[0], 'id'));
        $this->assertTrue(property_exists($productsList[0], 'name'));
        $this->assertTrue(property_exists($productsList[0], 'price'));
        $this->assertTrue(property_exists($productsList[0], 'images'));
        
        $this->assertTrue(isset($productsList[0]->id));
        $this->assertTrue(isset($productsList[0]->name));
        $this->assertTrue(isset($productsList[0]->price));
        $this->assertTrue(isset($productsList[0]->images));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
