<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\ProductsBrandsByIdBrandsMapper;
use app\models\{BrandsModel,
    ProductsBrandsModel};
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ProductsBrandsByIdBrandsMapper
 */
class ProductsBrandsByIdBrandsMapperTests extends \PHPUnit_Framework_TestCase
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
    private static $_brand = 'Night relax';
    
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_brands}} SET [[id_products]]=:id_products, [[id_brands]]=:id_brands');
        $command->bindValues([':id_products'=>self::$_id, ':id_brands'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ProductsBrandsByIdBrandsMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $productsBrandsByIdBrandsMapper = new ProductsBrandsByIdBrandsMapper([
            'tableName'=>'products_brands',
            'fields'=>['id_products', 'id_brands'],
            'model'=>new BrandsModel([
                'id'=>self::$_id,
            ]),
        ]);
        $productsBrandsArray = $productsBrandsByIdBrandsMapper->getGroup();
        
        $this->assertTrue(is_array($productsBrandsArray));
        $this->assertFalse(empty($productsBrandsArray));
        $this->assertTrue($productsBrandsArray[0] instanceof ProductsBrandsModel);
        
        $this->assertFalse(empty($productsBrandsArray[0]->id_products));
        $this->assertFalse(empty($productsBrandsArray[0]->id_brands));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
