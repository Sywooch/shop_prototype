<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\BrandsByBrandMapper;
use app\models\BrandsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\BrandsByBrandMapper
 */
class BrandsByBrandMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_brand = 'Dying basses';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод BrandsByBrandMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $brandsByBrandMapper = new BrandsByBrandMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'model'=>new BrandsModel([
                'brand'=>self::$_brand,
            ]),
        ]);
        $brandsModel = $brandsByBrandMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($brandsModel));
        $this->assertTrue($brandsModel instanceof BrandsModel);
        
        $this->assertEquals(self::$_id, $brandsModel->id);
        $this->assertEquals(self::$_brand, $brandsModel->brand);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
