<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\RelatedProductsMapper;
use app\models\ProductsModel;

/**
 * Тестирует класс app\mappers\RelatedProductsMapper
 */
class RelatedProductsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} (id,name,id_categories,id_subcategory) VALUES (:id1,:name1,:id_categories1,:id_subcategory1), (:id2,:name2,:id_categories2,:id_subcategory2)');
        $command->bindValues([':id1'=>self::$_id, ':name1'=>self::$_name, ':id_categories1'=>self::$_id, ':id_subcategory1'=>self::$_id, ':id2'=>self::$_id + 1, ':name2'=>self::$_name, ':id_categories2'=>self::$_id, ':id_subcategory2'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{related_products}} SET [[id_products]]=:id_products, [[id_related_products]]=:id_related_products');
        $command->bindValues([':id_products'=>self::$_id, ':id_related_products'=>self::$_id + 1]);
        $command->execute();
    }
    
    /**
     * Тестирует метод RelatedProductsMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = ['id'=>1, 'categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $relatedProductsMapper = new RelatedProductsMapper([
            'tableName'=>'products',
            'fields'=>['id', 'name', 'price', 'images'],
            'otherTablesFields'=>[
                ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
            ],
            'orderByField'=>'date',
            'model'=>new MockModel([
                'id'=>self::$_id
            ]),
        ]);
        $productsList = $relatedProductsMapper->getGroup();
        
        $this->assertTrue(is_array($productsList));
        $this->assertFalse(empty($productsList));
        $this->assertTrue(is_object($productsList[0]));
        $this->assertTrue($productsList[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productsList[0], 'id'));
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
