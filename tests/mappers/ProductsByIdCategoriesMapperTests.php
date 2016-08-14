<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\ProductsByIdCategoriesMapper;
use app\models\{ProductsModel,
    CategoriesModel};
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ProductsByIdCategoriesMapper
 */
class ProductsByIdCategoriesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 231;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ProductsByIdCategoriesMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $productsByIdCategoriesMapper = new ProductsByIdCategoriesMapper([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'code', 'name', 'description', 'price', 'images'],
            'model'=>new CategoriesModel([
                'id'=>self::$_id,
            ]),
        ]);
        $productsArray = $productsByIdCategoriesMapper->getGroup();
        
        $this->assertTrue(is_array($productsArray));
        $this->assertFalse(empty($productsArray));
        $this->assertTrue($productsArray[0] instanceof ProductsModel);
        
        $this->assertFalse(empty($productsArray[0]->id));
        $this->assertFalse(empty($productsArray[0]->date));
        $this->assertFalse(empty($productsArray[0]->code));
        $this->assertFalse(empty($productsArray[0]->name));
        $this->assertFalse(empty($productsArray[0]->description));
        $this->assertFalse(empty($productsArray[0]->price));
        $this->assertFalse(empty($productsArray[0]->images));
        
        $this->assertEquals(self::$_id, $productsArray[0]->id);
        $this->assertEquals(self::$_date, $productsArray[0]->date);
        $this->assertEquals(self::$_code, $productsArray[0]->code);
        $this->assertEquals(self::$_name, $productsArray[0]->name);
        $this->assertEquals(self::$_description, $productsArray[0]->description);
        $this->assertEquals(self::$_price, $productsArray[0]->price);
        $this->assertEquals(self::$_images, $productsArray[0]->images);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
