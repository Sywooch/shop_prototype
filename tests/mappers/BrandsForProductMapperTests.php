<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\BrandsForProductMapper;
use app\models\{BrandsModel, 
    ProductsModel};
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\BrandsForProductMapper
 */
class BrandsForProductMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_brand = 'Adidas';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_brands}} SET [[id_products]]=:id_products, [[id_brands]]=:id_brands');
        $command->bindValues([':id_products'=>self::$_id, ':id_brands'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод BrandsForProductMapper::getGroup
     */
    public function testGetGroup()
    {
        $brandsForProductMapper = new BrandsForProductMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'model'=>new ProductsModel([
                'id'=>self::$_id,
            ]),
        ]);
        $brandsObject = $brandsForProductMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($brandsObject));
        $this->assertTrue($brandsObject instanceof BrandsModel);
        
        $this->assertTrue(property_exists($brandsObject, 'id'));
        $this->assertTrue(property_exists($brandsObject, 'brand'));
        
        $this->assertFalse(empty($brandsObject->id));
        $this->assertFalse(empty($brandsObject->brand));
        
        $this->assertEquals(self::$_brand, $brandsObject->brand);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
